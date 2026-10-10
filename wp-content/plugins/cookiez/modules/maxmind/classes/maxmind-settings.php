<?php

namespace Cookiez\Modules\Maxmind\Classes;

use Cookiez\Modules\Settings\Classes\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Stored in its own WP option (rather than inside the generic `cookiez_settings`
 * array) since it is a secret that requires masking before it ever reaches the
 * browser.
 */
class Maxmind_Settings {
	public const PLACEHOLDER = 'DO_NOT_SHOW';

	public static function get(): string {
		$value = get_option( Settings::MAXMIND_LICENSE_KEY, '' );

		return is_string( $value ) ? $value : '';
	}

	/**
	 * Ignores the masked placeholder value, which is what the browser echoes
	 * back when the field wasn't touched.
	 */
	public static function set( string $key ): bool {
		$incoming = sanitize_text_field( strip_tags( $key ) );

		if ( self::PLACEHOLDER === $incoming ) {
			return false;
		}

		return update_option( Settings::MAXMIND_LICENSE_KEY, $incoming, false );
	}

	/**
	 * Returns the placeholder when a real key is stored, so the actual value
	 * is never exposed to the frontend/admin panel.
	 */
	public static function masked(): string {
		return '' !== self::get() ? self::PLACEHOLDER : '';
	}
}
