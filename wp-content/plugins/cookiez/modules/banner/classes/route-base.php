<?php

namespace Cookiez\Modules\Banner\Classes;

use Cookiez\Classes\Rest\Route;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Route_Base extends Route {
	protected bool $override = false;
	protected $auth = false;
	protected string $path = '';

	public function get_methods(): array {
		return [];
	}

	public function get_endpoint(): string {
		return 'banner/' . $this->get_path();
	}

	public function get_path(): string {
		return $this->path;
	}

	public function get_name(): string {
		return '';
	}
}
