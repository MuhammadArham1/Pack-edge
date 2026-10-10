<?php

namespace Cookiez\Modules\Maxmind\Classes;

use Cookiez\Classes\Logger;
use PharData;
use Throwable;
use WP_Filesystem_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Maxmind_Database_Manager {

	private const EDITION_ID = 'GeoLite2-Country';
	private const DOWNLOAD_ENDPOINT = 'https://download.maxmind.com/app/geoip_download';
	private const DB_UPDATED_AT_OPTION = 'cookiez_maxmind_db_updated_at';
	private const DOWNLOAD_TIMEOUT = 30;
	private const LOCK_TRANSIENT = 'cookiez_maxmind_db_lock';
	private const LOCK_TTL = 5 * MINUTE_IN_SECONDS;

	public static function get_database_path(): string {
		return self::get_storage_dir() . self::EDITION_ID . '.mmdb';
	}

	public static function has_database(): bool {
		$path = self::get_database_path();

		return file_exists( $path ) && is_readable( $path );
	}

	public static function get_last_updated_at(): ?int {
		$timestamp = get_option( self::DB_UPDATED_AT_OPTION );

		return $timestamp ? (int) $timestamp : null;
	}

	public static function download_database( string $license_key ): bool {
		$license_key = trim( $license_key );

		if ( '' === $license_key ) {
			return false;
		}

		if ( ! self::init_filesystem() ) {
			Logger::error( 'MaxMind download failed: could not initialize the WordPress filesystem.' );
			return false;
		}

		if ( ! self::acquire_lock() ) {
			Logger::warn( 'MaxMind download skipped: another download is already in progress.' );
			return false;
		}

		$archive_path = self::get_tmp_dir() . self::EDITION_ID . '-' . uniqid() . '.tar.gz';

		try {
			$response = wp_remote_get(
				self::build_download_url( $license_key ),
				[ 'timeout' => self::DOWNLOAD_TIMEOUT ]
			);

			if ( is_wp_error( $response ) ) {
				Logger::warn( 'MaxMind download failed: ' . $response->get_error_message() );
				return false;
			}

			$response_code = (int) wp_remote_retrieve_response_code( $response );

			if ( 200 !== $response_code ) {
				Logger::warn( 'MaxMind download returned unexpected status code: ' . $response_code );
				return false;
			}

			$body = wp_remote_retrieve_body( $response );

			if ( '' === $body ) {
				Logger::warn( 'MaxMind download returned an empty response body.' );
				return false;
			}

			global $wp_filesystem;

			if ( ! $wp_filesystem->put_contents( $archive_path, $body, FS_CHMOD_FILE ) ) {
				Logger::error( 'MaxMind download failed: could not write the downloaded archive to disk.' );
				return false;
			}

			return self::extract_and_store( $archive_path );
		} catch ( Throwable $e ) {
			Logger::error( 'MaxMind download failed: ' . $e->getMessage() );
			return false;
		} finally {
			self::delete_path( dirname( $archive_path ) );
			self::release_lock();
		}
	}

	private static function extract_and_store( string $archive_path ): bool {
		$extract_dir = dirname( $archive_path ) . '/extracted';

		try {
			$decompressed = ( new PharData( $archive_path ) )->decompress();
			$decompressed->extractTo( $extract_dir );

			$mmdb_files = glob( $extract_dir . '/*/' . self::EDITION_ID . '.mmdb' );

			if ( empty( $mmdb_files[0] ) ) {
				Logger::error( 'MaxMind archive did not contain the expected database file.' );
				return false;
			}

			$destination = self::get_database_path();
			wp_mkdir_p( dirname( $destination ) );

			global $wp_filesystem;

			if ( ! $wp_filesystem->move( $mmdb_files[0], $destination, true ) ) {
				Logger::error( 'MaxMind database could not be moved into place.' );
				return false;
			}

			update_option( self::DB_UPDATED_AT_OPTION, time(), false );

			return true;
		} catch ( Throwable $e ) {
			Logger::error( 'MaxMind archive extraction failed: ' . $e->getMessage() );
			return false;
		}
	}

	private static function build_download_url( string $license_key ): string {
		return add_query_arg(
			[
				'edition_id'  => self::EDITION_ID,
				'license_key' => rawurlencode( $license_key ),
				'suffix'      => 'tar.gz',
			],
			self::DOWNLOAD_ENDPOINT
		);
	}

	private static function get_storage_dir(): string {
		$upload_dir = wp_upload_dir();

		return trailingslashit( $upload_dir['basedir'] ) . 'cookiez/';
	}

	private static function get_tmp_dir(): string {
		$dir = self::get_storage_dir() . 'tmp/';
		wp_mkdir_p( $dir );

		return $dir;
	}

	private static function delete_path( string $path ): void {
		global $wp_filesystem;

		if ( $wp_filesystem instanceof WP_Filesystem_Base && $wp_filesystem->exists( $path ) ) {
			$wp_filesystem->delete( $path, true );
		}
	}

	private static function acquire_lock(): bool {
		if ( false !== get_transient( self::LOCK_TRANSIENT ) ) {
			return false;
		}

		return set_transient( self::LOCK_TRANSIENT, time(), self::LOCK_TTL );
	}

	private static function release_lock(): void {
		delete_transient( self::LOCK_TRANSIENT );
	}

	private static function init_filesystem(): bool {
		global $wp_filesystem;

		if ( $wp_filesystem instanceof WP_Filesystem_Base ) {
			return true;
		}

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		return (bool) WP_Filesystem();
	}
}
