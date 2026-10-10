<?php

namespace Cookiez\Modules\Maxmind\Classes;

use Cookiez\Classes\Rest\Route;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Route_Base
 */
class Route_Base extends Route {
	protected bool $override = false;
	protected $auth = true;
	protected string $path = '';
	public function get_methods(): array {
		return [];
	}

	/**
	 * Routes are namespaced under `settings/` since the license key is
	 * managed from the Settings admin page and consumed by its frontend.
	 */
	public function get_endpoint(): string {
		return 'settings/' . $this->get_path();
	}

	public function get_path(): string {
		return $this->path;
	}

	public function get_name(): string {
		return '';
	}
}
