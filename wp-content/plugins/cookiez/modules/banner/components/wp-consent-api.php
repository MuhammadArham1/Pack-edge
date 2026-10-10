<?php

namespace Cookiez\Modules\Banner\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Cookiez\Classes\Utils;
use Cookiez\Modules\Settings\Classes\Settings;

class Wp_Consent_Api {

	private const CONSENT_CATEGORY_MAP = [
		'functional'  => 'functional',
		'analytics'   => 'statistics',
		'advertising' => 'marketing',
	];

	private const WP_CONSENT_API_HANDLE = 'wp-consent-api';

	public function __construct() {
		$plugin = COOKIEZ_PLUGIN_BASE;
		add_filter( "wp_consent_api_registered_{$plugin}", '__return_true' );
		add_filter( 'wp_get_consent_type', [ self::class, 'get_consent_type' ] );
		add_filter( 'wp_consent_api_waitfor_consent_hook', '__return_true' );
		add_action( 'wp_enqueue_scripts', [ self::class, 'attach_initial_consent_script' ], PHP_INT_MAX - 99 );
	}

	public static function get_consent_type(): string {
		$settings     = Settings::get( Settings::COOKIEZ_SETTINGS );
		$template_type = $settings['templateType'] ?? 'opt-in';

		return 'opt-out' === $template_type ? 'optout' : 'optin';
	}

	/**
	 * Attaches the initial consent script after `wp-consent-api`'s own script,
	 * since `wp_set_consent()` is only defined once that script has loaded.
	 */
	public static function attach_initial_consent_script(): void {
		$settings = Settings::get( Settings::COOKIEZ_SETTINGS );

		if ( empty( $settings['supportGcm'] ) || ! wp_script_is( self::WP_CONSENT_API_HANDLE, 'registered' ) ) {
			return;
		}

		wp_add_inline_script(
			self::WP_CONSENT_API_HANDLE,
			implode( "\n", self::build_consent_script_lines() ),
			'after'
		);
	}

	public static function build_consent_script_lines(): array {
		$consent_type   = self::get_consent_type();
		$stored_consent = Utils::parse_consent_cookie();

		$lines = [
			'window.wp_consent_type = ' . wp_json_encode( $consent_type ) . ';',
			"document.dispatchEvent(new CustomEvent('wp_consent_type_defined'));",
		];

		if ( is_array( $stored_consent ) ) {
			foreach ( self::CONSENT_CATEGORY_MAP as $cookiez_category => $wp_category ) {
				$status  = ! empty( $stored_consent[ $cookiez_category ] ) ? 'allow' : 'deny';
				$lines[] = 'wp_set_consent(' . wp_json_encode( $wp_category ) . ',' . wp_json_encode( $status ) . ');';
			}
		}

		return $lines;
	}
}
