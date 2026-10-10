<?php

namespace Cookiez\Modules\Deactivation;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Cookiez\Classes\Module_Base;
use Cookiez\Classes\Utils;
use Cookiez\Modules\Settings\Classes\Settings;

/**
 * Deactivation feedback module
 */
class Module extends Module_Base {

	/**
	 * @return string
	 */
	public function get_name(): string {
		return 'Deactivation';
	}

	/**
	 * @return array
	 */
	public static function routes_list(): array {
		return [
			'Feedback',
		];
	}

	/**
	 * Check if we should show the deactivation feedback modal
	 *
	 * @return bool
	 */
	private function should_show_feedback(): bool {
		global $pagenow;

		return 'plugins.php' === $pagenow && current_user_can( 'manage_options' );
	}

	/**
	 * Enqueue deactivation feedback assets
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {
		Utils\Assets::enqueue_app_assets( 'deactivation', false );

		wp_localize_script(
			'deactivation',
			'cookiezDeactivationData',
			[
				'wpRestNonce'   => wp_create_nonce( 'wp_rest' ),
				'deactivateUrl' => '',
				'isRTL'         => is_rtl(),
				'isDevelopment' => defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG,
				'pluginEnv'     => apply_filters( 'cookiez_settings_plugin_env', 'production' ),
				'appVersion'    => COOKIEZ_VERSION,
				'wpVersion'     => get_bloginfo( 'version' ),
				'planData'      => get_option( Settings::PLAN_DATA ),
				'planScope'     => get_option( Settings::PLAN_SCOPE ),
			]
		);
	}

	/**
	 * Print the deactivation app mount point
	 *
	 * @return void
	 */
	public function print_app_mount(): void {
		echo '<div id="deactivation-app"></div>';
	}

	/**
	 * Module constructor.
	 */
	public function __construct() {
		$this->register_routes();

		if ( ! $this->should_show_feedback() ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_footer', [ $this, 'print_app_mount' ] );
	}
}
