<?php

namespace Cookiez\Modules\Maxmind\Rest;

use Cookiez\Modules\Maxmind\Classes\Maxmind_Settings;
use Cookiez\Modules\Maxmind\Classes\Route_Base;
use Throwable;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Update_Maxmind_License_Key extends Route_Base {
	public string $path = 'maxmind-license-key';

	public function get_methods(): array {
		return [ 'POST' ];
	}

	public function get_name(): string {
		return 'update-maxmind-license-key';
	}

	public function POST( WP_REST_Request $request ) {
		try {
			if ( ! array_key_exists( 'maxmindLicenseKey', $this->params ) ) {
				return $this->respond_error_json( [
					'message' => __( 'Invalid request body.', 'cookiez' ),
					'code'    => 'invalid_body',
				], 400 );
			}

			Maxmind_Settings::set( (string) $this->params['maxmindLicenseKey'] );

			return $this->respond_success_json( [
				'maxmindLicenseKey' => Maxmind_Settings::masked(),
			] );
		} catch ( Throwable $t ) {
			return $this->respond_error_json( [
				'message' => $t->getMessage(),
				'code'    => 'internal_server_error',
			], 500 );
		}
	}
}
