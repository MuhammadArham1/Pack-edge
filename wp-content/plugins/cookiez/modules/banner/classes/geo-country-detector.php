<?php

namespace Cookiez\Modules\Banner\Classes;

use Cookiez\Modules\Maxmind\Classes\Maxmind_Geo_Reader;
use WC_Geolocation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Geo_Country_Detector {

	private const COUNTRY_HEADERS = [
		'HTTP_CF_IPCOUNTRY',
		'HTTP_CLOUDFRONT_VIEWER_COUNTRY',
		'HTTP_X_SUCURI_COUNTRY',
		'HTTP_X_VERCEL_IP_COUNTRY',
		'HTTP_X_COUNTRY_CODE',
		'HTTP_X_COUNTRY',
		'HTTP_X_GEO_COUNTRY',
	];

	private const ISO_COUNTRY_CODE_PATTERN = '/^[A-Z]{2}$/';

	public static function detect_country_code(): ?string {
		$code = self::detect_from_headers();

		if ( null !== $code ) {
			return $code;
		}

		$code = self::detect_from_woocommerce();

		if ( null !== $code ) {
			return $code;
		}

		return self::detect_from_maxmind();
	}

	private static function detect_from_headers(): ?string {
		foreach ( self::COUNTRY_HEADERS as $header_key ) {
			$code = self::read_country_header( $header_key );

			if ( null !== $code ) {
				return $code;
			}
		}

		return null;
	}

	private static function detect_from_woocommerce(): ?string {
		if ( ! class_exists( 'WooCommerce' ) || ! class_exists( WC_Geolocation::class ) ) {
			return null;
		}

		$user_ip     = WC_Geolocation::get_ip_address();
		$geolocation = WC_Geolocation::geolocate_ip( $user_ip );

		return self::normalize_country_code( $geolocation['country'] ?? null );
	}

	private static function detect_from_maxmind(): ?string {
		$ip = self::get_visitor_ip();

		if ( null === $ip ) {
			return null;
		}

		return Maxmind_Geo_Reader::lookup_country_code( $ip );
	}

	private static function get_visitor_ip(): ?string {
		if ( empty( $_SERVER['REMOTE_ADDR'] ) ) {
			return null;
		}

		$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );

		return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : null;
	}

	private static function read_country_header( string $key ): ?string {
		if ( empty( $_SERVER[ $key ] ) ) {
			return null;
		}

		$raw = sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) );

		return self::normalize_country_code( $raw );
	}

	private static function normalize_country_code( $raw ): ?string {
		if ( ! is_string( $raw ) || '' === $raw ) {
			return null;
		}

		$code = strtoupper( trim( $raw ) );

		return preg_match( self::ISO_COUNTRY_CODE_PATTERN, $code ) ? $code : null;
	}
}
