<?php

namespace Cookiez\Modules\Maxmind\Classes;

use Cookiez\Classes\Logger;
use GeoIp2\Database\Reader;
use Throwable;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Maxmind_Geo_Reader {

	private const ISO_COUNTRY_CODE_PATTERN = '/^[A-Z]{2}$/';

	public static function lookup_country_code( string $ip ): ?string {
		if ( ! class_exists( Reader::class ) || ! Maxmind_Database_Manager::has_database() ) {
			return null;
		}

		try {
			$reader = new Reader( Maxmind_Database_Manager::get_database_path() );
			$code   = $reader->country( $ip )->country->isoCode;

			return is_string( $code ) && preg_match( self::ISO_COUNTRY_CODE_PATTERN, $code ) ? $code : null;
		} catch ( Throwable $e ) {
			Logger::warn( 'MaxMind lookup failed: ' . $e->getMessage() );
			return null;
		}
	}
}
