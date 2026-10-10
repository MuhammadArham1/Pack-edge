<?php

namespace Cookiez\Modules\Banner\Rest;

use Cookiez\Modules\Banner\Classes\Geo_Country_Detector;
use Cookiez\Modules\Banner\Classes\Route_Base;
use WP_REST_Response;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Get_Geo extends Route_Base {
	protected $auth = false;
	public string $path = 'geo';

	public function get_methods(): array {
		return [ 'GET' ];
	}

	public function get_name(): string {
		return 'get-geo';
	}

	public function GET(): WP_REST_Response {
		return $this->respond_success_json( [
			'countryCode' => Geo_Country_Detector::detect_country_code(),
		] );
	}
}
