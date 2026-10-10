<?php

namespace Cookiez\Modules\Maxmind;

use Cookiez\Classes\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Module `Maxmind`
 * Owns the MaxMind GeoLite2 database download/update logic and the license
 * key storage/REST endpoint consumed by the Settings and Banner modules.
 */
class Module extends Module_Base {
	public function get_name(): string {
		return 'Maxmind';
	}

	public static function component_list(): array {
		return [
			'Maxmind_Database_Updater',
		];
	}

	public static function routes_list(): array {
		return [
			'Update_Maxmind_License_Key',
		];
	}

	public function __construct() {
		$this->register_components();
		$this->register_routes();
	}
}
