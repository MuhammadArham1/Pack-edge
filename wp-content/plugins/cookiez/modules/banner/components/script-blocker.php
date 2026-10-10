<?php

namespace Cookiez\Modules\Banner\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Cookiez\Classes\Enums\Cookie_Category;
use Cookiez\Classes\Utils;
use Cookiez\Modules\Banner\Module;
use Cookiez\Modules\Script\Classes\Enums\Script_Blocking_Mode;
use Cookiez\Modules\Script\Database\Script_Entry;
use Cookiez\Modules\Settings\Classes\Settings;
use WP_HTML_Tag_Processor;

class Script_Blocker {

	public const IGNORED_SCRIPT_PATTERNS = [
		'plugins/cookiez/assets/build/banner.js',
		'plugins/cookiez/assets/build/gutenberg-blocks/preferences-link/frontend.js',
	];

	private const PATTERN_TYPE_FILE = 'file';
	private const PATTERN_TYPE_INLINE = 'inline';

	public const GOOGLE_SCRIPT_PATTERNS = [
		'googletagmanager.com',
		'google-analytics.com',
		'googleadservices.com',
		'googlesyndication.com',
		'doubleclick.net',
		'google.com/ccm',
		'gtag/js',
	];

	//TODO: Remove once the scanner is fixed (see APP-2852)
	private const IGNORED_SCRIPT_VALUES = [ '/' ];

	private array $blockable_scripts = [];
	private array $consent = [];
	private array $used_patterns = [];
	private array $google_bypass_patterns = [];
	private ?int $output_buffer_base_level = null;

	public function start_buffering(): void {
		if ( null !== $this->output_buffer_base_level ) {
			return;
		}

		$this->output_buffer_base_level = ob_get_level();
		$buffer_started = ob_start( [ $this, 'process_buffer' ] );

		if ( ! $buffer_started ) {
			$this->output_buffer_base_level = null;

			return;
		}

		add_action(
			'shutdown',
			function () {
				$this->end_output_buffering();
			}
		);
	}

	private function end_output_buffering(): void {
		if ( null === $this->output_buffer_base_level ) {
			return;
		}

		$base = $this->output_buffer_base_level;
		$this->output_buffer_base_level = null;

		if ( ob_get_level() <= $base ) {
			return;
		}

		while ( ob_get_level() > $base ) {
			ob_end_flush();
		}
	}

	public function process_buffer( string $html ): string {
		$processor = new WP_HTML_Tag_Processor( $html );

		while ( $processor->next_tag( 'script' ) ) {
			if ( $this->is_exempt_script_type( $processor->get_attribute( 'type' ) ) ) {
				continue;
			}

			$raw_src = $processor->get_attribute( 'src' );
			$inline = $processor->get_modifiable_text();
			$outer_html = $this->reconstruct_script_outer_html( $processor, $inline );

			if ( $this->matches_patterns( $raw_src, $outer_html, self::IGNORED_SCRIPT_PATTERNS ) ) {
				continue;
			}
			$meta = $this->get_blocked_script_meta( $raw_src, $outer_html );

			if ( null === $meta ) {
				continue;
			}

			$processor->set_attribute( 'type', 'text/plain' );
			$processor->set_attribute( 'data-cc-category', $meta['category'] );
			$processor->set_attribute( 'data-cc-mode', $meta['mode'] );

			if ( null !== $meta['file_src'] ) {
				$processor->set_attribute( 'data-cc-src', $meta['file_src'] );
				$processor->remove_attribute( 'src' );
			}
		}

		return $processor->get_updated_html();
	}

	private function reconstruct_script_outer_html( WP_HTML_Tag_Processor $processor, string $inline ): string {
		$names = $processor->get_attribute_names_with_prefix( '' ) ?? [];
		$attr_string = '';

		foreach ( $names as $name ) {
			$value = $processor->get_attribute( $name );

			if ( true === $value ) {
				$attr_string .= ' ' . $name;
				continue;
			}

			if ( null === $value || false === $value ) {
				continue;
			}

			$attr_string .= ' ' . $name . '="' . esc_attr( (string) $value ) . '"';
		}

		return '<script' . $attr_string . '>' . $inline . '</script>';
	}

	private function matches_patterns( ?string $raw_src, string $outer_html, array $patterns ): bool {
		if ( empty( $patterns ) ) {
			return false;
		}

		foreach ( $patterns as $pattern ) {
			if ( ! empty( $raw_src ) && str_contains( (string) $raw_src, $pattern ) ) {
				return true;
			}

			if ( str_contains( $outer_html, $pattern ) ) {
				return true;
			}
		}

		return false;
	}

	public static function should_allow_google_before_consent(): bool {
		$settings = Settings::get( Settings::COOKIEZ_SETTINGS );

		return ! empty( $settings['supportGcm'] ) && ! empty( $settings['googleTagsBeforeConsent'] );
	}

	public static function get_google_bypass_patterns(): array {
		return self::should_allow_google_before_consent() ? self::GOOGLE_SCRIPT_PATTERNS : [];
	}

	private function is_exempt_script_type( ?string $type_attribute ): bool {
		if ( null === $type_attribute || '' === trim( $type_attribute ) ) {
			return false;
		}

		$mediatype = trim( explode( ';', $type_attribute, 2 )[0] );
		$mediatype = strtolower( $mediatype );

		return in_array( $mediatype, [ 'application/json', 'text/plain' ], true );
	}

	private function get_blocked_script_meta( ?string $raw_src, string $outer_html ): ?array {
		$is_google_script = $this->matches_patterns( $raw_src, $outer_html, $this->google_bypass_patterns );

		foreach ( $this->blockable_scripts as $mode => $categories ) {
			$is_bypassed = Script_Blocking_Mode::ALWAYS !== $mode && $is_google_script;

			foreach ( $categories as $category => $scripts ) {
				if ( Script_Blocking_Mode::ALWAYS !== $mode && ! empty( $this->consent[ $category ] ) ) {
					continue;
				}

				if ( $raw_src ) {
					$matched_file = $this->find_unused_pattern(
						self::PATTERN_TYPE_FILE,
						$raw_src,
						$scripts['files'] ?? []
					);

					if ( null !== $matched_file ) {
						if ( $is_bypassed ) {
							return null;
						}

						$this->mark_pattern_used( self::PATTERN_TYPE_FILE, $matched_file );

						return [
							'category' => $category,
							'mode'     => $mode,
							'file_src' => (string) $raw_src,
						];
					}
				}

				$matched_inline = $this->find_unused_pattern(
					self::PATTERN_TYPE_INLINE,
					$outer_html,
					$scripts['inline'] ?? []
				);

				if ( null !== $matched_inline ) {
					if ( $is_bypassed ) {
						return null;
					}

					$this->mark_pattern_used( self::PATTERN_TYPE_INLINE, $matched_inline );

					return [
						'category' => $category,
						'mode'     => $mode,
						'file_src' => null,
					];
				}
			}
		}

		return null;
	}

	private function find_unused_pattern( string $type, string $haystack, array $patterns ): ?string {
		foreach ( $patterns as $pattern ) {
			if ( $this->is_pattern_used( $type, $pattern ) ) {
				continue;
			}

			if ( str_contains( $haystack, $pattern ) ) {
				return $pattern;
			}
		}

		return null;
	}

	private function is_pattern_used( string $type, string $pattern ): bool {
		return isset( $this->used_patterns[ $type . ':' . $pattern ] );
	}

	private function mark_pattern_used( string $type, string $pattern ): void {
		$this->used_patterns[ $type . ':' . $pattern ] = true;
	}

	public static function parse_consent_cookie(): array {
		return Utils::parse_consent_cookie() ?? [];
	}

	public static function get_blockable_scripts(): array {
		$scripts = [];

		foreach ( Script_Entry::find_all() as $row ) {
			if ( Cookie_Category::NECESSARY === $row->category || empty( $row->value ) ) {
				continue;
			}

			if ( in_array( trim( (string) $row->value ), self::IGNORED_SCRIPT_VALUES, true ) ) {
				continue;
			}

			$mode = empty( $row->blocking_mode ) ? Script_Blocking_Mode::UNTIL_CONSENT : $row->blocking_mode;

			if ( Script_Blocking_Mode::NEVER === $mode ) {
				continue;
			}

			$category = $row->category;

			if ( ! isset( $scripts[ $mode ][ $category ] ) ) {
				$scripts[ $mode ][ $category ] = [
					'files'  => [],
					'inline' => [],
				];
			}

			$type_key = 'inline' === ( $row->type ?? '' ) ? 'inline' : 'files';
			$scripts[ $mode ][ $category ][ $type_key ][] = $row->value;
		}

		return $scripts;
	}

	public function __construct() {
		if ( ! Module::should_blocker_run() ) {
			return;
		}

		$settings = Settings::get( Settings::COOKIEZ_SETTINGS );
		$is_opt_out = 'opt-out' === ( $settings['templateType'] ?? 'opt-in' );

		$this->blockable_scripts = self::get_blockable_scripts();

		$this->consent = self::parse_consent_cookie();

		$this->google_bypass_patterns = self::get_google_bypass_patterns();

		$has_always = ! empty( $this->blockable_scripts[ Script_Blocking_Mode::ALWAYS ] );

		if ( $is_opt_out && empty( $this->consent ) && ! $has_always ) {
			return;
		}

		if ( empty( $this->blockable_scripts ) ) {
			return;
		}

		add_action( 'template_redirect', [ $this, 'start_buffering' ] );
	}
}
