<?php

namespace Cookiez\Modules\Banner;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Cookiez\Classes\Module_Base;
use Cookiez\Classes\Utils;
use Cookiez\Classes\Utils\Integration_Detect;
use Cookiez\Modules\Banner\Dynamic_Tags\Preferences_Trigger;
use Cookiez\Modules\Connect\Module as Connect_Module;
use Cookiez\Modules\Cookie\Database\Cookie_Entry;
use Cookiez\Modules\Settings\Classes\Sanitize_Content;
use Cookiez\Modules\Settings\Classes\Settings;

/**
 * Module `Banner`
 */
class Module extends Module_Base {

	public const CONSENT_COOKIE_NAME = 'cookiez-user-consent';

	private const SCANNER_USER_AGENT_MARKERS = [
		'Cookiez/',
		'elementor.com',
	];

	private array $page_cookies = [];
	private array $settings     = [];

	public function get_name(): string {
		return 'Banner';
	}

	public static function component_list(): array {
		$components = [
			'Gutenberg_Preferences_Link_Block',
			'Script_Blocker',
			'Script_Blocker_Fallback',
			'Gcm_Inline_Script',
		];

		if ( Integration_Detect::should_sync_wp_consent_api() ) {
			$components[] = 'Wp_Consent_Api';
		}

		return $components;
	}

	public static function routes_list(): array {
		return [
			'Get_Geo',
		];
	}

	/**
	 * Get service URL
	 *
	 * @return string
	 */
	public static function get_service_api_url(): string {
		return apply_filters( 'cookiez_service_api_url', 'https://my.elementor.com/apps/api/v1/cookiez-consent' );
	}

	/**
	 * Enqueue Scripts and Styles
	 */
	public function enqueue_scripts(): void {
		if ( Utils::is_elementor_editor() ) {
			return;
		}

		Utils\Assets::enqueue_app_assets( 'banner', false );

		$content            = Settings::get( Settings::COOKIEZ_CONTENT );
		$default_language   = $content['languages'][0] ?? Sanitize_Content::FALLBACK_LANGUAGE;
		$disabled_languages = $content['disabledLanguages'] ?? [];
		$plan_data = get_option( Settings::PLAN_DATA );

		$banner_settings = [
			'isDevelopment' => defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG,
			'isRTL'         => is_rtl(),
			'language'      => Sanitize_Content::resolve_language( get_locale(), $default_language, $disabled_languages ),
			'publicApiKey'  => $plan_data->public_api_key ?? '',
			'url'           => Utils::get_current_page_url(),
			'serviceUrl'    => self::get_service_api_url(),
			'settings'      => $this->settings,
			'content'       => $content,
			'cookies'       => $this->page_cookies,
			'translations'  => Utils::get_translations(),
			'integrations'  => [
				'wpConsentApiActive'   => Integration_Detect::is_wp_consent_api_active(),
				'siteKitConsentMode'   => Integration_Detect::is_site_kit_consent_mode_enabled(),
				'delegateGcmToSiteKit' => Integration_Detect::should_delegate_gcm_to_site_kit(),
			],
			'cookiesHash'   => $this->get_cookies_hash(),
			'geoEndpoint'   => rest_url( 'cookiez/v1/banner/geo' ),
			'wpRestNonce'   => wp_create_nonce( 'wp_rest' ),
		];

		$banner_settings = apply_filters( 'cookiez/banner/settings', $banner_settings );

		wp_localize_script(
			'banner',
			'cookiezBannerSettings',
			$banner_settings
		);
	}


	public static function should_blocker_run(): bool {
		if ( is_admin() ) {
			return false;
		}

		if ( self::is_cookiez_scanner_request() ) {
			return false;
		}

		if ( ! Connect_Module::is_connected() ) {
			return false;
		}

		$settings = Settings::get( Settings::COOKIEZ_SETTINGS );

		if ( array_key_exists( 'bannerDisplayStatus', $settings ) && ! $settings['bannerDisplayStatus'] ) {
			return false;
		}

		$disabled_pages = $settings['disableBannerPages'] ?? [];

		if ( empty( $disabled_pages ) || ! is_array( $disabled_pages ) ) {
			return true;
		}

		$current_url = home_url( add_query_arg( null, null ) );

		foreach ( $disabled_pages as $url ) {
			if ( trailingslashit( $current_url ) === trailingslashit( $url ) ) {
				return false;
			}
		}

		return true;
	}

	private static function is_cookiez_scanner_request(): bool {
		if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return false;
		}

		$ua = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );

		foreach ( self::SCANNER_USER_AGENT_MARKERS as $marker ) {
			if ( ! str_contains( $ua, $marker ) ) {
				return false;
			}
		}

		return true;
	}

	// Check if cookies or cookies category was changed
	private function get_cookies_hash(): string {
		$signature = array_map(
			fn( $cookie ) => $cookie->name . ':' . $cookie->category,
			$this->page_cookies
		);
		sort( $signature );
		$signature[] = 'template:' . ( $this->settings['templateType'] ?? 'opt-in' );

		return md5( implode( '|', $signature ) );
	}

	/**
	 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
	 * @return void
	 */
	public function register_dynamic_tag( $dynamic_tags_manager ): void {
		if ( ! class_exists( '\Elementor\Core\DynamicTags\Data_Tag' ) ) {
			return;
		}

		$dynamic_tags_manager->register( new Preferences_Trigger() );
	}

	/**
	 * Registers Elementor Pro URL action handler (inline script on a dedicated handle).
	 *
	 * @return void
	 */
	public function enqueue_elementor_prefs_url_action(): void {
		if ( is_admin() ) {
			return;
		}

		wp_add_inline_script(
			'banner',
			"
			(function() {
				const registerCookiezPreferencesAction = () => {
					if ( ! window?.ElementorProFrontendConfig || ! window?.elementorFrontend?.utils?.urlActions ) {
						return;
					}

					elementorFrontend.utils.urlActions.addAction( 'cookiezBanner:openPreferences', () => {
						window?.cookiezBanner?.screenManager?.openPreferences?.();
					} );
				};

				const waitingLimit = 30;
				let retryCounter = 0;

				const waitForElementorPro = () => {
					return new Promise( ( resolve ) => {
						const intervalId = setInterval( () => {
							if ( retryCounter === waitingLimit ) {
								resolve( null );
							}

							retryCounter++;

							if ( window.elementorFrontend && window?.elementorFrontend?.utils?.urlActions ) {
								clearInterval( intervalId );
								resolve( window.elementorFrontend );
							}
						}, 100 );
					});
				};

				waitForElementorPro().then( () => { registerCookiezPreferencesAction(); } );
			}());
		"
		);
	}

	public function __construct() {
		add_action( 'elementor/dynamic_tags/register', [ $this, 'register_dynamic_tag' ] );

		$this->register_routes();

		if ( ! self::should_blocker_run() ) {
			$this->register_components( [ 'Gutenberg_Preferences_Link_Block' ] );
			return;
		}

		$this->settings = Settings::get( Settings::COOKIEZ_SETTINGS );

		$this->page_cookies = Cookie_Entry::find_all();

		$this->register_components();

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_elementor_prefs_url_action' ], 20 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}
}
