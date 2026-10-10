<?php

namespace Cookiez\Modules\Deactivation\Classes;

use Cookiez\Classes\Services\Client;
use Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Feedback_Client {

	private const SERVICE_ENDPOINT = 'feedback/deactivation';

	/**
	 * Send deactivation feedback to the service.
	 *
	 * @param array<string, mixed> $params Request payload.
	 *
	 * @return mixed
	 * @throws Exception When the request fails.
	 */
	public static function post_feedback( array $params ) {
		$response = Client::get_instance()->make_request(
			'POST',
			self::SERVICE_ENDPOINT,
			$params
		);

		if ( empty( $response ) || is_wp_error( $response ) ) {
			throw new Exception( 'Failed to send deactivation feedback.' );
		}

		return $response;
	}
}
