<?php

namespace Cookiez\Classes\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Cookiez\Modules\Settings\Classes\Settings;

class Integration_Detect {

	private const SITE_KIT_PLUGIN_FILE         = 'google-site-kit/google-site-kit.php';
	private const SITE_KIT_CONSENT_MODE_OPTION = 'googlesitekit_consent_mode';
	private const WP_CONSENT_API_PLUGIN_FILE   = 'wp-consent-api/wp-consent-api.php';

	public static function is_site_kit_active(): bool {
		return is_plugin_active( self::SITE_KIT_PLUGIN_FILE );
	}

	public static function is_site_kit_consent_mode_enabled(): bool {
		if ( ! self::is_site_kit_active() ) {
			return false;
		}

		$option = get_option( self::SITE_KIT_CONSENT_MODE_OPTION );

		return is_array( $option ) && ! empty( $option['enabled'] );
	}

	public static function is_wp_consent_api_active(): bool {
		return is_plugin_active( self::WP_CONSENT_API_PLUGIN_FILE );
	}

	public static function should_sync_wp_consent_api(): bool {
		$settings = Settings::get( Settings::COOKIEZ_SETTINGS );

		return ! empty( $settings['supportGcm'] ) && self::is_wp_consent_api_active();
	}

	public static function should_delegate_gcm_to_site_kit(): bool {
		return self::is_site_kit_consent_mode_enabled() && self::should_sync_wp_consent_api();
	}

	public static function should_show_site_kit_notice(): bool {
		return self::is_site_kit_consent_mode_enabled() && ! self::is_wp_consent_api_active();
	}
}
