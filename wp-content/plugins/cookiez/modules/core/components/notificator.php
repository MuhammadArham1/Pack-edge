<?php

namespace Cookiez\Modules\Core\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\WPNotificationsPackage\V120\Notifications as Notifications_SDK;

class Notificator {
	private ?Notifications_SDK $notificator = null;

	public function get_notifications_by_conditions( bool $force_request = false ): array {
		return $this->notificator->get_notifications_by_conditions( $force_request );
	}

	public function __construct() {
		require_once COOKIEZ_PATH . '/vendor/autoload.php';

		$this->notificator = new Notifications_SDK( [
			'app_name'       => 'cookie-consent',
			'app_version'    => COOKIEZ_VERSION,
			'short_app_name' => 'ecc',
			'app_data'       => [
				'plugin_basename' => COOKIEZ_PLUGIN_BASE,
			],
		] );
	}
}
