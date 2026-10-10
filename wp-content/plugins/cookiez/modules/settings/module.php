<?php

namespace Cookiez\Modules\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Cookiez\Classes\Logger;
use Cookiez\Classes\Module_Base;
use Cookiez\Classes\Utils;
use Cookiez\Classes\Utils\Integration_Detect;
use Cookiez\Modules\Connect\Classes\Config;
use Cookiez\Modules\Connect\Module as Connect;
use Cookiez\Modules\Core\Components\Notices;
use Cookiez\Modules\Maxmind\Classes\Maxmind_Settings;
use Cookiez\Modules\Settings\Banners\Elementor_Birthday_Banner;
use Cookiez\Modules\Settings\Classes\Settings;
use Throwable;
use Exception;
use WP_Error;

/**
 * Module `Settings`
 */
class Module extends Module_Base {
	const SETTING_BASE_SLUG = 'cookiez-settings';
	const SETTING_CAPABILITY = 'manage_options';
	const WP_CONSENT_API_LEARN_MORE_URL = 'https://go.elementor.com/cookie-consent-with-site-kit';

	public function get_name(): string {
		return 'Settings';
	}

	/**
	 * Declare components list
	 *
	 * @return array
	 */
	public static function component_list(): array {
		return [
			'Settings_Pointer',
		];
	}

	/**
	 * Get Plugin ENV
	 * @return string
	 */
	private static function get_plugin_env(): string {
		return apply_filters( 'cookiez_settings_plugin_env', 'production' );
	}

	public function render_app() {
		?>
		<?php Elementor_Birthday_Banner::get_banner( 'https://go.elementor.com/cookiez-10th-bd-sale' ); ?>
		<!-- The hack required to wrap WP notifications -->
		<div class="wrap">
			<h1 style="display: none;" role="presentation"></h1>
		</div>

		<div id="cookiez-app"></div>
		<?php
	}

	public function register_page() {
		add_menu_page(
			__( 'Cookie Consent', 'cookiez' ),
			__( 'Cookiez', 'cookiez' ),
			self::SETTING_CAPABILITY,
			self::SETTING_BASE_SLUG,
			[ $this, 'render_app' ],
			self::get_menu_icon(),
			59
		);
	}

	/**
	 * Get the base64-encoded SVG icon used for the Cookiez top-level menu item.
	 *
	 * @return string
	 */
	private static function get_menu_icon(): string {
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
			<path fill-rule="evenodd" clip-rule="evenodd" d="M12.5059 1.2959C13.2796 1.33351 13.8048 1.88691 14.0508 2.41016C14.5691 3.51276 15.6888 4.27432 16.9844 4.27441C17.4752 4.27449 17.9059 4.50662 18.2012 4.80176C18.4963 5.097 18.7285 5.52781 18.7285 6.01855C18.7287 7.53595 19.7737 8.81216 21.1836 9.16309C21.8044 9.31775 22.5261 9.80688 22.6338 10.6729C22.6878 11.1076 22.7168 11.5508 22.7168 12L22.7021 12.5518C22.4148 18.2139 17.7333 22.7176 12 22.7178C6.26658 22.7177 1.58524 18.2139 1.29785 12.5518L1.2832 12C1.28371 6.0816 6.08169 1.28333 12 1.2832C12.1697 1.28321 12.3387 1.28781 12.5059 1.2959ZM15.2832 13.7725C14.4295 13.7726 13.7373 14.4647 13.7373 15.3184C13.7373 16.1721 14.4295 16.8641 15.2832 16.8643C16.137 16.8643 16.8291 16.1722 16.8291 15.3184C16.8291 14.4646 16.137 13.7725 15.2832 13.7725ZM8.50488 12.9072C7.54124 12.9075 6.75987 13.6887 6.75977 14.6523C6.75977 15.6161 7.54117 16.3972 8.50488 16.3975C9.46879 16.3975 10.25 15.6163 10.25 14.6523C10.2499 13.6885 9.46873 12.9072 8.50488 12.9072ZM10.7969 6.9248C9.66825 6.92516 8.75316 7.84009 8.75293 8.96875C8.75293 10.0976 9.6681 11.0133 10.7969 11.0137C11.926 11.0137 12.8418 10.0978 12.8418 8.96875C12.8416 7.83987 11.9258 6.9248 10.7969 6.9248Z" fill="#a7aaad"/>
			<path d="M20.8496 4C21.3191 4 21.7 4.38015 21.7002 4.84961C21.7002 5.31925 21.3192 5.7002 20.8496 5.7002C20.3801 5.69998 20 5.31912 20 4.84961C20.0002 4.38028 20.3802 4.00021 20.8496 4Z" fill="#a7aaad"/>
			<path d="M18.4316 2C18.6686 2 18.8631 2.19355 18.8633 2.42969C18.8633 2.66596 18.6687 2.86035 18.4316 2.86035C18.1945 2.86032 18 2.66382 18 2.42969C18.0002 2.19568 18.1946 2.00003 18.4316 2Z" fill="#a7aaad"/>
		</svg>';

		return 'data:image/svg+xml;base64,' . base64_encode( $svg );
	}

	/**
	 * Enqueue Scripts and Styles
	 */
	public function enqueue_scripts(): void {
		if ( ! Utils::is_plugin_page() ) {
			return;
		}

		/**
		 * These styles are braking MUI component styling.
		 * Re-registering as empty so wp-admin's dependency doesn't break
	 */
		wp_deregister_style( 'forms' );
		wp_register_style( 'forms', false );

		self::refresh_plan_data();

		wp_enqueue_style(
			'cookiez-admin-fonts',
			'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap',
			[],
			COOKIEZ_VERSION
		);

		Utils\Assets::enqueue_app_assets( 'settings' );

		$settings_data = [
			'isConnected' => Connect::is_connected(),
			'isUrlMismatch' => ! Connect::get_connect()->utils()->is_valid_home_url(),
			'wpRestNonce' => wp_create_nonce( 'wp_rest' ),
			'restRoot' => rest_url(),
			'pluginEnv' => self::get_plugin_env(),
			'isDevelopment' => defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG,
			'appSlug' => Config::PLUGIN_SLUG,
			'appVersion' => COOKIEZ_VERSION,
			'wpVersion' => get_bloginfo( 'version' ),
			'isRTL' => is_rtl(),
			'dateFormat' => get_option( 'date_format', 'F j, Y' ),
			'timeFormat' => get_option( 'time_format', 'g:i a' ),
			'translations' => Utils::get_translations(),
			'isElementorOne' => self::is_elementor_one(),
			'hasElementorOneSubscription' => self::has_elementor_one_subscription(),
			'settings' => Settings::get( Settings::COOKIEZ_SETTINGS ),
			'maxmindLicenseKey' => Maxmind_Settings::masked(),
			'content' => Settings::get( Settings::COOKIEZ_CONTENT ),
			'planData' => get_option( Settings::PLAN_DATA ),
			'planScope' => get_option( Settings::PLAN_SCOPE ),
			'isElementorActive' => defined( 'ELEMENTOR_VERSION' ),
			'isElementorProActive' => defined( 'ELEMENTOR_PRO_VERSION' ),
			'integrations' => [
				'wpConsentApiActive'     => Integration_Detect::is_wp_consent_api_active(),
				'siteKitActive'          => Integration_Detect::is_site_kit_active(),
				'siteKitConsentMode'     => Integration_Detect::is_site_kit_consent_mode_enabled(),
				'delegateGcmToSiteKit'   => Integration_Detect::should_delegate_gcm_to_site_kit(),
				'wpConsentApiInstallUrl' => admin_url( 'plugin-install.php?s=wp-consent-api&tab=search&type=term' ),
				'wpConsentApiLearnMoreUrl' => self::WP_CONSENT_API_LEARN_MORE_URL,
			],
		];

		$settings_data = apply_filters( 'cookiez/settings/data', $settings_data );

		wp_localize_script(
			'settings',
			'cookiezSettingsData',
			$settings_data
		);
	}

	/**
	 * Check if cookie consent is connected to elementor one
	 * @return bool
	 */
	public static function is_elementor_one(): bool {
		return Connect::get_connect()->get_config( 'app_type' ) !== Config::APP_TYPE;
	}

	/**
	 * Check if user generally has an active Elementor One subscription,
	 * @return bool
	 */
	public static function has_elementor_one_subscription(): bool {
		$one_facade = \ElementorOne\Admin\Helpers\Utils::get_one_connect();
		return $one_facade && $one_facade->utils()->is_connected();
	}

	/**
	 * Get all plugin settings data
	 * @return array
	 * @throws Throwable
	 */
	public static function get_plugin_settings(): array {
		$content = Settings::get( Settings::COOKIEZ_CONTENT );

		return array_merge(
			[
				'isDevelopment' => defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG,
				'siteUrl' => wp_parse_url( get_site_url(), PHP_URL_HOST ),
				'settings' => Settings::get( Settings::COOKIEZ_SETTINGS ),
				'maxmindLicenseKey' => Maxmind_Settings::masked(),
			],
			is_array( $content ) ? $content : []
		);
	}

	public static function routes_list(): array {
		return [
			'Get_Settings',
			'Update_Settings',
			'Get_Consent_Logs',
			'Export_Consent_Logs',
		];
	}

	/**
	 * @throws Exception
	 */
	public function on_connect(): void {
		if ( ! Connect::is_connected() ) {
			return;
		}

		self::register_site_with_data();
	}

	/**
	 * Fetch and save plan data on Elementor One app_cookie connection.
	 * @return void
	 */
	public function on_app_cookie_connected(): void {
		if ( ! Connect::is_connected() ) {
			return;
		}

		$plan_data = get_option( Settings::PLAN_DATA );

		$response = Utils::get_api_client()->make_request(
			'POST',
			'sites/info'
		);

		self::save_plan_data( $response );
	}

	/**
	 * On disconnect
	 * @return void
	 */
	public function on_disconnect() {
		delete_option( Settings::SUBSCRIPTION_ID );
	}

	/**
	 * Register or update site data for One connect
	 * @throws Exception
	 */
	public function on_migration_run() {
		if ( ! Connect::is_connected() ) {
			return;
		}

		$client_id = Settings::get( Settings::CLIENT_ID );

		if ( $client_id ) {
			try {
				$migration_response = Utils::get_api_client()->make_request(
					'POST',
					'site/migration',
					[ 'old_client_id' => $client_id ],
				);

				self::save_plan_data( $migration_response );

				$old_options = [
					'cookiez_client_secret',
					'cookiez_home_url',
					'cookiez_access_token',
					'cookiez_token_id',
					'cookiez_refresh_token',
					'cookiez_user_access_token',
					'cookiez_owner_user_id',
					Settings::SUBSCRIPTION_ID,
					Settings::CLIENT_ID,
				];

				foreach ( $old_options as $option ) {
					delete_option( $option );
				}
			} catch ( Throwable $t ) {
				Logger::error( esc_html( $t->getMessage() ) );
			}
		} else {
			$this->on_connect();
		}
	}

	/**
	 * Register the website and save the plan data.
	 * @return void
	 */
	public static function register_site_with_data(): void {
		$register_response = Utils::get_api_client()->make_request(
			'POST',
			'site/register'
		);

		if ( is_wp_error( $register_response ) ) {
			Logger::error( esc_html( $register_response->get_error_message() ) );
		} else {
			self::save_plan_data( $register_response );
			if ( isset( $register_response->scopes ) ) {
				Settings::set( Settings::PLAN_SCOPE, $register_response->scopes );
			}
		}
	}

	/**
	 * Refresh the plan data if the refresh transient has expired.
	 * @return void
	 */
	public static function refresh_plan_data(): void {
		if ( ! Connect::is_connected() ) {
			return;
		}

		if ( self::get_plan_data_refresh_transient() ) {
			return;
		}

		$plan_data = get_option( Settings::PLAN_DATA );

		if ( empty( $plan_data->public_api_key ) ) {
			Logger::error( 'Cannot refresh the plan data. No public API key found.' );
			self::register_site_with_data();
			return;
		}

		$response = Utils::get_api_client()->make_request(
			'POST',
			'site/info'
		);

		self::save_plan_data( $response );
	}

	public static function set_plan_data_refresh_transient(): void {
		set_transient( Settings::PLAN_DATA_REFRESH_TRANSIENT, true, MINUTE_IN_SECONDS * 15 );
	}

	public static function get_plan_data_refresh_transient(): bool {
		return get_transient( Settings::PLAN_DATA_REFRESH_TRANSIENT );
	}

	public static function delete_plan_data_refresh_transient(): bool {
		return delete_transient( Settings::PLAN_DATA_REFRESH_TRANSIENT );
	}

	/**
	 * Save plan data to plan_data option
	 * @param $register_response
	 *
	 * @return void
	 */
	public static function save_plan_data( $register_response ): void {
		if ( $register_response && ! is_wp_error( $register_response ) ) {
			$decoded_response = $register_response;

			update_option( Settings::SUBSCRIPTION_ID, $decoded_response->plan->subscription_id );
			update_option( Settings::PLAN_DATA, $decoded_response );
			update_option( Settings::IS_VALID_PLAN_DATA, true );

			self::set_plan_data_refresh_transient();
		} else {
			Logger::error( $register_response instanceof WP_Error ? esc_html( $register_response->get_error_message() ) : $register_response );
			update_option( Settings::IS_VALID_PLAN_DATA, false );
		}
	}

	/**
	 * Get upgrade link with UTM parameters
	 *
	 * @param string $campaign Campaign identifier for tracking.
	 * @return string
	 */
	public static function get_upgrade_link( string $campaign ): string {
		$subscription_id = get_option( Settings::SUBSCRIPTION_ID );

		if ( $subscription_id ) {
			return add_query_arg(
				[
					'utm_source'      => $campaign . '-upgrade',
					'utm_medium'      => 'wp-dash',
					'subscription_id' => $subscription_id,
				],
				'https://go.elementor.com/' . $campaign
			);
		}

		return add_query_arg(
			[
				'utm_source' => $campaign . '-upgrade',
				'utm_medium' => 'wp-dash',
			],
			'https://go.elementor.com/' . $campaign
		);
	}

	/**
	 * Cookie consent quota usage percentage (cookie_consents only).
	 *
	 * @return float Percent used (0–100+), or 0 if unavailable.
	 */
	public static function get_consent_quota_usage_percent(): float {
		$plan_data = get_option( Settings::PLAN_DATA );

		if ( ! $plan_data || ! isset( $plan_data->quota->cookie_consents ) ) {
			return 0;
		}

		$consents = $plan_data->quota->cookie_consents;

		if ( ! isset( $consents->allowed, $consents->used ) || $consents->allowed <= 0 ) {
			return 0;
		}

		return round( $consents->used / $consents->allowed * 100, 2 );
	}

	/**
	 * Register quota notices with the notice manager.
	 *
	 * @param Notices $notice_manager The notice manager instance.
	 * @return void
	 */
	public function register_notices( Notices $notice_manager ): void {
		if ( self::is_elementor_one() ) {
			$this->register_one_notices( $notice_manager );
			return;
		}

		if ( ! Connect::is_connected() && ! get_option( Settings::PLAN_DATA ) ) {
			return;
		}

		$notices = [
			'Quota_80',
			'Quota_100',
			'Site_Kit_Wp_Consent_Api',
		];

		foreach ( $notices as $notice ) {
			$class_name = 'Cookiez\Modules\Settings\Notices\\' . $notice;
			$notice_manager->register_notice( new $class_name() );
		}
	}

		/**
	 * Register quota notices with the notice manager.
	 *
	 * @param Notices $notice_manager The notice manager instance.
	 * @return void
	 */
	public function register_one_notices( Notices $notice_manager ): void {
		if ( ! self::is_elementor_one() ) {
			return;
		}

		$notices = [
			'Site_Kit_Wp_Consent_Api',
		];

		foreach ( $notices as $notice ) {
			$class_name = 'Cookiez\Modules\Settings\Notices\\' . $notice;
			$notice_manager->register_notice( new $class_name() );
		}
	}

	public function __construct() {
		$this->register_components();
		$this->register_routes();

		add_action( 'admin_menu', [ $this, 'register_page' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_action( 'elementor_one/' . Config::APP_PREFIX . '_connected', [ $this, 'on_connect' ] );
		add_action( 'elementor_one/' . Config::APP_PREFIX . '_disconnected', [ $this, 'on_disconnect' ] );
		add_action( 'elementor_one/' . Config::APP_PREFIX . '_migration_run', [ $this, 'on_migration_run' ] );
		add_action( 'elementor_one/' . Config::APP_TYPE . '_connected', [ $this, 'on_app_cookie_connected' ] );

		add_action( 'cookiez_register_notices', [ $this, 'register_notices' ] );
	}
}
