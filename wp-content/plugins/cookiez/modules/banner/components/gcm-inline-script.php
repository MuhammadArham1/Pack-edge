<?php

namespace Cookiez\Modules\Banner\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Cookiez\Classes\Utils;
use Cookiez\Classes\Utils\Integration_Detect;
use Cookiez\Modules\Settings\Classes\Settings;

class Gcm_Inline_Script {

	private const GCM_CATEGORY_MAP = [
		'analytics'   => [ 'analytics_storage' ],
		'advertising' => [ 'ad_storage', 'ad_user_data', 'ad_personalization' ],
	];

	public function __construct() {
		add_action( 'wp_head', [ self::class, 'output' ], 0 );
	}

	public static function output(): void {
		$settings = Settings::get( Settings::COOKIEZ_SETTINGS );

		if ( empty( $settings['supportGcm'] ) ) {
			return;
		}

		if ( Integration_Detect::should_delegate_gcm_to_site_kit() ) {
			return;
		}

		$is_advanced_mode = ! empty( $settings['googleTagsBeforeConsent'] );
		$is_opt_out       = 'opt-out' === ( $settings['templateType'] ?? 'opt-in' );
		$default_state    = $is_opt_out ? 'granted' : 'denied';
		$stored_consent   = Utils::parse_consent_cookie();

		$script = "window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}\n";
		$script .= self::build_default_call( $default_state, $is_advanced_mode );

		if ( null !== $stored_consent ) {
			$script .= self::build_update_call( $stored_consent );
		}

		wp_print_inline_script_tag( $script );
	}

	private static function build_default_call( string $default_state, bool $is_advanced_mode ): string {
		$args = [
			'analytics_storage'  => $default_state,
			'ad_storage'         => $default_state,
			'ad_user_data'       => $default_state,
			'ad_personalization' => $default_state,
		];

		if ( $is_advanced_mode ) {
			$args['wait_for_update'] = 500;
		}

		return "gtag('consent','default'," . wp_json_encode( $args ) . ");\n";
	}

	private static function build_update_call( array $consent ): string {
		$payload = [];

		foreach ( self::GCM_CATEGORY_MAP as $category => $signals ) {
			$state = ! empty( $consent[ $category ] ) ? 'granted' : 'denied';
			foreach ( $signals as $signal ) {
				$payload[ $signal ] = $state;
			}
		}

		return "gtag('consent','update'," . wp_json_encode( $payload ) . ");\n";
	}
}
