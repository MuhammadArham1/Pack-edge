<?php

namespace Cookiez\Modules\Deactivation\Rest;

use Cookiez\Classes\Rest\{
	Route,
	Sanitizer,
	Validator,
};
use Cookiez\Modules\Deactivation\Classes\Feedback_Client;
use Throwable;
use WP_REST_Request;
use WP_REST_Response;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Feedback extends Route {

	protected bool $override = false;

	public function get_methods(): array {
		return [ 'POST' ];
	}

	public function get_name(): string {
		return 'feedback';
	}

	public function get_endpoint(): string {
		return 'deactivation/feedback';
	}

	protected function sanitize_fields(): array {
		return [
			'reason'          => Sanitizer::key(),
			'additional_data' => Sanitizer::textarea(),
		];
	}

	protected function validate_fields(): array {
		return [
			'reason' => ( new Validator() )->string( [
				'message' => esc_html__( 'Reason is required.', 'cookiez' ),
			] ),
			'additional_data' => ( new Validator() )
				->string()
				->nullable(),
		];
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public function POST( WP_REST_Request $request ): WP_REST_Response {
		try {
			$error = $this->verify_capability();

			if ( $error ) {
				return $error;
			}

			$errors = $this->validate( $this->params );

			if ( ! empty( $errors ) ) {
				return $this->respond_validation_error( $errors );
			}

			$payload = [
				'app'             => 'cookiez',
				'selected_answer' => $this->params['reason'],
			];

			if ( ! empty( $this->params['additional_data'] ) ) {
				$payload['feedback_text'] = $this->params['additional_data'];
			}

			$response = Feedback_Client::post_feedback( $payload );

			return $this->respond_success_json( $response );

		} catch ( Throwable $t ) {
			return $this->respond_error_json( [
				'message' => $t->getMessage(),
				'code'    => 'internal_server_error',
			], 500 );
		}
	}
}
