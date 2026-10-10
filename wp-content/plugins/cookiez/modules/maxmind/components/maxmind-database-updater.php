<?php

namespace Cookiez\Modules\Maxmind\Components;

use Cookiez\Classes\Maxmind_Cron_Scheduler;
use Cookiez\Modules\Maxmind\Classes\Maxmind_Database_Manager;
use Cookiez\Modules\Maxmind\Classes\Maxmind_Settings;
use Cookiez\Modules\Settings\Classes\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Maxmind_Database_Updater {

	public function __construct() {
		add_action( Maxmind_Cron_Scheduler::CRON_HOOK, [ $this, 'maybe_update_database' ] );
		add_action( 'update_option_' . Settings::MAXMIND_LICENSE_KEY, [ $this, 'maybe_schedule_immediate_download' ], 10, 2 );
	}

	/**
	 * Runs on the monthly MaxMind cron tick. Skips the download entirely when
	 */
	public function maybe_update_database(): void {
		$license_key = Maxmind_Settings::get();

		if ( '' === $license_key ) {
			return;
		}

		Maxmind_Database_Manager::download_database( $license_key );
	}

	/**
	 * @param mixed $old_value
	 * @param mixed $new_value
	 */
	public function maybe_schedule_immediate_download( $old_value, $new_value ): void {
		$old_key = is_string( $old_value ) ? $old_value : '';
		$new_key = is_string( $new_value ) ? $new_value : '';

		if ( '' === $new_key || $new_key === $old_key ) {
			return;
		}

		$this->schedule_immediate_download();
	}

	/**
	 * Schedules an ASAP single MaxMind download run, unless one is already
	 * imminent (avoids piling up duplicate single events on repeated saves).
	 */
	private function schedule_immediate_download(): void {
		$next_run = wp_next_scheduled( Maxmind_Cron_Scheduler::CRON_HOOK );
		$imminent_threshold = time() + 5 * MINUTE_IN_SECONDS;

		if ( ! $next_run || $next_run > $imminent_threshold ) {
			wp_schedule_single_event( time(), Maxmind_Cron_Scheduler::CRON_HOOK );
		}
	}
}
