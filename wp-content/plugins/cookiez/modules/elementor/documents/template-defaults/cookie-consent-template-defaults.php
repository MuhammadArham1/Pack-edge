<?php

namespace Cookiez\Modules\Elementor\Documents\Template_Defaults;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Cookiez\Modules\Elementor\Documents\Template_Defaults_Base;

class Cookie_Consent_Template_Defaults extends Template_Defaults_Base {

	protected static function get_default_template_data(): array {
		return [
			[
				'id'       => self::generate_element_id(),
				'elType'   => 'container',
				'settings' => [
					'content_width' => 'full',
					'flex_gap' => [
						'unit' => 'px',
						'size' => 0,
						'row' => '0',
						'column' => '0',
						'isLinked' => true,
					],
				],
				'elements' => [
					[
						'id'         => self::generate_element_id(),
						'elType'     => 'widget',
						'widgetType' => 'cookiez-heading',
						'settings'   => [],
						'elements'   => [],
					],
					[
						'id'         => self::generate_element_id(),
						'elType'     => 'widget',
						'widgetType' => 'divider',
						'settings'   => [
							'color' => 'rgba(0, 0, 0, 0.12)',
						],
						'elements'   => [],
					],
					[
						'id'         => self::generate_element_id(),
						'elType'     => 'widget',
						'widgetType' => 'cookiez-content',
						'settings'   => [],
						'elements'   => [],
					],
					[
						'id'         => self::generate_element_id(),
						'elType'     => 'widget',
						'widgetType' => 'cookiez-footer',
						'settings'   => [],
						'elements'   => [],
					],
					[
						'id'         => self::generate_element_id(),
						'elType'     => 'widget',
						'widgetType' => 'divider',
						'settings'   => [
							'color' => 'rgba(0, 0, 0, 0.12)',
						],
						'elements'   => [],
					],
					[
						'id'       => self::generate_element_id(),
						'elType'   => 'container',
						'settings' => [
							'content_width'        => 'full',
							'flex_direction'       => 'row',
							'flex_justify_content' => 'center',
							'flex_align_items'     => 'center',
							'flex_gap'             => [
								'unit'     => 'px',
								'size'     => 8,
								'column'   => '8',
								'row'      => '8',
								'isLinked' => true,
							],
							'padding'              => [
								'top'      => '0',
								'right'    => '0',
								'bottom'   => '0',
								'left'     => '0',
								'unit'     => 'px',
								'isLinked' => true,
							],
						],
						'elements' => [
							[
								'id'         => self::generate_element_id(),
								'elType'     => 'widget',
								'widgetType' => 'heading',
								'settings'   => [
									'title'                 => 'Cookie Consent by',
									'header_size'           => 'span',
									'align'                 => 'center',
									'link'                  => [
										'url'         => 'https://go.elementor.com/cookiez-consent-banner-footer-main',
										'is_external' => 'on',
									],
								],
								'elements'   => [],
							],
							[
								'id'         => self::generate_element_id(),
								'elType'     => 'widget',
								'widgetType' => 'image',
								'settings'   => [
									'image'   => [
										'url' => ELEMENTOR_URL . 'assets/images/logo-icon.png',
										'id'  => '',
									],
									'link_to' => 'custom',
									'link'    => [
										'url'         => 'https://go.elementor.com/cookiez-consent-banner-footer-main',
										'is_external' => 'on',
									],
									'width'   => [
										'size' => 20,
										'unit' => 'px',
									],
								],
								'elements'   => [],
							],
						],
					],
				],
			],
		];
	}
}
