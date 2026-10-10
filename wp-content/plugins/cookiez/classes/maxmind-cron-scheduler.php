<?php

namespace Cookiez\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Maxmind_Cron_Scheduler {
	public const CRON_HOOK = 'cookiez_maxmind_monthly_update';
	public const CRON_INTERVAL = 'cookiez_monthly';

	private const MONTH_IN_SECONDS = 30 * DAY_IN_SECONDS;

	public function __construct( $file ) {
		add_filter( 'cron_schedules', [ $this, 'register_interval' ] ); //phpcs:ignore WordPress.WP.CronInterval.CronSchedulesInterval

		// Activation only fires on a fresh install or a deactivate/reactivate cycle, so a
		// plugin update on an already-active site would otherwise never get the cron scheduled.
		add_action( 'init', [ $this, 'maybe_schedule' ] );
		register_deactivation_hook( $file, [ $this, 'deactivate' ] );
	}

	/**
	 * @param array<string, array{interval: int, display: string}> $schedules
	 * @return array<string, array{interval: int, display: string}>
	 */
	public function register_interval( array $schedules ): array {
		$schedules[ self::CRON_INTERVAL ] = [
			'interval' => self::MONTH_IN_SECONDS,
			'display'  => __( 'Once a month', 'cookiez' ),
		];

		return $schedules;
	}

	public function maybe_schedule(): void {
		if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			wp_schedule_event( time(), self::CRON_INTERVAL, self::CRON_HOOK );
		}
	}

	public function deactivate(): void {
		wp_clear_scheduled_hook( self::CRON_HOOK );
	}
}
