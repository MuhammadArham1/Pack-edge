<?php

namespace Cookiez\Modules\Settings\Notices;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Cookiez\Classes\Utils\Integration_Detect;
use Cookiez\Classes\Utils\Notice_Base;
use Cookiez\Modules\Settings\Module as Settings_Module;

class Site_Kit_Wp_Consent_Api extends Notice_Base {
	public string $type = 'warning';
	public bool $is_dismissible = true;
	public bool $per_user = true;
	public $capability = 'manage_options';
	public string $id = 'site-kit-wp-consent-api-notice';

	public function content(): string {
		return sprintf(
			'<h3>%s</h3><p>%s</p><p><a class="button button-secondary" href="%s" target="_blank" rel="noopener noreferrer">%s</a> <a class="button button-primary" href="%s" target="_blank" rel="noopener noreferrer">%s</a></p>',
			esc_html__( 'Action needed: Connect Site Kit to Cookie Consent', 'cookiez' ),
			esc_html__(
				'Site Kit\'s Consent Mode is on but isn\'t connected to Elementor\'s Cookie Consent, so Google may not receive your visitors\' actual choices. Install the free WP Consent API plugin to connect them — as Google recommends.',
				'cookiez'
			),
			esc_url( Settings_Module::WP_CONSENT_API_LEARN_MORE_URL ),
			esc_html__( 'Learn more', 'cookiez' ),
			esc_url( admin_url( 'plugin-install.php?s=wp-consent-api&tab=search&type=term' ) ),
			esc_html__( 'Install WP Consent API', 'cookiez' )
		);
	}

	public function maybe_add_site_kit_wp_consent_api_notice(): void {
		if ( ! Integration_Detect::should_show_site_kit_notice() ) {
			$this->conditions = false;
			return;
		}

		$this->conditions = true;
	}

	public function __construct() {
		add_action( 'current_screen', [ $this, 'maybe_add_site_kit_wp_consent_api_notice' ] );

		parent::__construct();
	}
}
