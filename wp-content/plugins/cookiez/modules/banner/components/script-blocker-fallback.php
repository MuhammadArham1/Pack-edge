<?php

namespace Cookiez\Modules\Banner\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Cookiez\Modules\Banner\Module;
use Cookiez\Modules\Script\Classes\Enums\Script_Blocking_Mode;
use Cookiez\Modules\Settings\Classes\Settings;

class Script_Blocker_Fallback {

	private const DATA_TAG_ID = 'cc-blocked-scripts';

	private array $scripts = [];

	/**
	 * render blocker fallback script
	 *
	 * @return void
	 */
	public function render(): void {
		wp_print_inline_script_tag(
			wp_json_encode( $this->scripts ),
			[
				'id'   => self::DATA_TAG_ID,
				'type' => 'application/json',
			]
		);

		wp_print_inline_script_tag( $this->get_blocker_inline_script() );
	}

	/**
	 * get_blocker_inline_script
	 *
	 * returns the fallback script logic
	 *
	 * @return string
	 */
	private function get_blocker_inline_script(): string {
		$data_tag_id = esc_js( self::DATA_TAG_ID );
		$cookie_name = esc_js( Module::CONSENT_COOKIE_NAME );
		$always_mode = esc_js( Script_Blocking_Mode::ALWAYS );
		$ignored_json = wp_json_encode( Script_Blocker::IGNORED_SCRIPT_PATTERNS );
		$google_bypass_json = wp_json_encode( Script_Blocker::get_google_bypass_patterns() );

		return "(function() {
	const dataTag = document.getElementById('{$data_tag_id}');

	if (!dataTag) {
		return;
	}

	const blockedScripts = JSON.parse(dataTag.textContent);
	const cookieName = '{$cookie_name}';

	const readConsent = () => {
		const match = document.cookie.match(new RegExp('(?:^|;\\\\s*)' + cookieName + '=([^;]*)'));

		if (!match) {
			return {};
		}

		try {
			const parsed = JSON.parse(decodeURIComponent(match[1]));

			return (parsed.data && parsed.data.consent) || {};
		} catch (e) {
			return {};
		}
	};

	const consent = readConsent();
	const ALWAYS_MODE = '{$always_mode}';
	const IGNORED_PATTERNS = {$ignored_json};
	const GOOGLE_BYPASS_PATTERNS = {$google_bypass_json};
	const FILE_TYPE = 'file';
	const INLINE_TYPE = 'inline';
	const usedPatterns = new Set();

	const matchesPatterns = (src, outerHtml, patterns) =>
		patterns.some((pattern) => (src && src.indexOf(pattern) !== -1) || outerHtml.indexOf(pattern) !== -1);

	const findUnusedPattern = (type, haystack, patterns) => {
		for (let i = 0; i < patterns.length; i++) {
			const pattern = patterns[i];

			if (usedPatterns.has(type + ':' + pattern)) {
				continue;
			}

			if (haystack.indexOf(pattern) !== -1) {
				return pattern;
			}
		}

		return null;
	};

	const blockScript = (script) => {
		if (script.type === 'application/json' || script.type === 'text/plain') {
			return;
		}

		const src = script.getAttribute('src');

		if (src && IGNORED_PATTERNS.some((pattern) => src.indexOf(pattern) !== -1)) {
			return;
		}

		const outerHtml = script.outerHTML || '';
		const isGoogleScript = matchesPatterns(src, outerHtml, GOOGLE_BYPASS_PATTERNS);

		for (const mode in blockedScripts) {
			const isBypassed = mode !== ALWAYS_MODE && isGoogleScript;
			const categories = blockedScripts[mode];

			for (const category in categories) {
				if (mode !== ALWAYS_MODE && consent[category] === true) {
					continue;
				}

				const { files = [], inline = [] } = categories[category];
				const matchedFile = src ? findUnusedPattern(FILE_TYPE, src, files) : null;

				if (matchedFile !== null) {
					if (isBypassed) {
						return;
					}

					usedPatterns.add(FILE_TYPE + ':' + matchedFile);
					script.setAttribute('data-cc-src', src);
					script.removeAttribute('src');
					script.type = 'text/plain';
					script.setAttribute('data-cc-category', category);
					script.setAttribute('data-cc-mode', mode);

					return;
				}

				const matchedInline = findUnusedPattern(INLINE_TYPE, outerHtml, inline);

				if (matchedInline !== null) {
					if (isBypassed) {
						return;
					}

					usedPatterns.add(INLINE_TYPE + ':' + matchedInline);
					script.type = 'text/plain';
					script.setAttribute('data-cc-category', category);
					script.setAttribute('data-cc-mode', mode);

					return;
				}
			}
		}
	};

	new MutationObserver((mutations) => {
		mutations.forEach((mutation) => {
			mutation.addedNodes.forEach((node) => {
				if (node.nodeName === 'SCRIPT') {
					blockScript(node);
				}
			});
		});
	}).observe(document.documentElement, { childList: true, subtree: true });
})();";
	}

	public function __construct() {
		// Prevent script blocking if banner disabled
		if ( ! Module::should_blocker_run() ) {
			return;
		}

		$scripts = Script_Blocker::get_blockable_scripts();
		$consent = Script_Blocker::parse_consent_cookie();

		$settings = Settings::get( Settings::COOKIEZ_SETTINGS );
		$is_opt_out = 'opt-out' === ( $settings['templateType'] ?? 'opt-in' );

		$has_always = ! empty( $scripts[ Script_Blocking_Mode::ALWAYS ] );

		if ( $is_opt_out && empty( $consent ) && ! $has_always ) {
			return;
		}

		if ( empty( $scripts ) ) {
			return;
		}

		$this->scripts = $scripts;

		add_action( 'wp_head', [ $this, 'render' ], 1 );
	}
}
