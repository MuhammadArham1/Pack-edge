<?php

namespace Cookiez\Classes\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Webhook_Token
 * Per-site token used to authenticate inbound webhook calls from the external
 * scanning service.
 */
final class Webhook_Token {
	private const OPTION_KEY = 'cookiez_scan_webhook_token';

	/**
	 * Return the stored token, generating one on first read.
	 */
	public static function get(): string {
		$token = (string) get_option( self::OPTION_KEY, '' );

		if ( '' === $token ) {
			$token = wp_generate_password( 64, false );
			update_option( self::OPTION_KEY, $token, false );
		}

		return $token;
	}

	/**
	 * Replace the stored token with a fresh one. Callers must push the new
	 * webhook URL to the service (via `Client::register_website()` or the
	 * next `stats` POST) — the service stores the URL verbatim and will
	 * keep POSTing to the stale URL otherwise.
	 */
	public static function rotate(): string {
		$token = wp_generate_password( 64, false );
		update_option( self::OPTION_KEY, $token, false );

		return $token;
	}
}
