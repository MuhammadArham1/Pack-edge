<?php

namespace Cookiez\Modules\Settings\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sanitize_Settings {
	private const ALLOWED_ONBOARDING_STEPS = [ 'step_one', 'step_two', 'step_three' ];
	private const ALLOWED_TEMPLATE_TYPES = [ 'opt-in', 'opt-out', 'opt-in-out' ];
	private const ALLOWED_GEO_TARGETING = [ 'worldwide', 'adaptive' ];
	private const ALLOWED_FALLBACK_MODELS = [ 'opt-in', 'opt-out', 'no-banner' ];
	private const MAX_CONSENT_EXPIRATION_DAYS = 365;
	private const MIN_CONSENT_EXPIRATION_DAYS = 1;
	private const ISO_CODE_PATTERN = '/^[a-z]{2}$/';

	/**
	 * @param array<string, mixed> $raw
	 * @return array<string, mixed>
	 */
	public static function sanitize( array $raw ): array {
		$out = [];

		if ( array_key_exists( 'bannerDisplayStatus', $raw ) ) {
			$out['bannerDisplayStatus'] = (bool) $raw['bannerDisplayStatus'];
		}

		if ( array_key_exists( 'disableBannerPages', $raw ) && is_array( $raw['disableBannerPages'] ) ) {
			$out['disableBannerPages'] = array_values( array_filter(
				array_map( 'esc_url_raw', $raw['disableBannerPages'] )
			) );
		}

		if ( isset( $raw['templateType'] ) && in_array( $raw['templateType'], self::ALLOWED_TEMPLATE_TYPES, true ) ) {
			$out['templateType'] = $raw['templateType'];
		}

		if ( isset( $raw['geoTargeting'] ) && in_array( $raw['geoTargeting'], self::ALLOWED_GEO_TARGETING, true ) ) {
			$out['geoTargeting'] = $raw['geoTargeting'];
		}

		$template_type = $raw['templateType'] ?? null;
		if ( 'opt-in-out' === $template_type && isset( $raw['regionalRules'] ) && is_array( $raw['regionalRules'] ) ) {
			$out['regionalRules'] = self::sanitize_regional_rules( $raw['regionalRules'] );
		}

		if ( array_key_exists( 'regionalRulesAlertDismissed', $raw ) ) {
			$out['regionalRulesAlertDismissed'] = (bool) $raw['regionalRulesAlertDismissed'];
		}

		if ( array_key_exists( 'consentExpiration', $raw ) ) {
			$out['consentExpiration'] = min(
				self::MAX_CONSENT_EXPIRATION_DAYS,
				max( self::MIN_CONSENT_EXPIRATION_DAYS, absint( $raw['consentExpiration'] ) )
			);
		}

		if ( array_key_exists( 'gpcDntSupport', $raw ) ) {
			$out['gpcDntSupport'] = (bool) $raw['gpcDntSupport'];
		}

		if ( array_key_exists( 'supportGcm', $raw ) ) {
			$out['supportGcm'] = (bool) $raw['supportGcm'];
		}

		if ( array_key_exists( 'googleTagsBeforeConsent', $raw ) ) {
			$out['googleTagsBeforeConsent'] = (bool) $raw['googleTagsBeforeConsent'];
		}

		if ( array_key_exists( 'contentAlertDismissed', $raw ) ) {
			$out['contentAlertDismissed'] = (bool) $raw['contentAlertDismissed'];
		}

		if ( array_key_exists( 'designBannerInfotipDismissed', $raw ) ) {
			$out['designBannerInfotipDismissed'] = (bool) $raw['designBannerInfotipDismissed'];
		}

		if ( array_key_exists( 'isMigrationPopupDismissed', $raw ) ) {
			$out['isMigrationPopupDismissed'] = (bool) $raw['isMigrationPopupDismissed'];
		}

		if ( array_key_exists( 'isOnboardingCompleted', $raw ) ) {
			$out['isOnboardingCompleted'] = (bool) $raw['isOnboardingCompleted'];
		}

		if ( isset( $raw['onboardingCurrentStep'] ) && in_array( $raw['onboardingCurrentStep'], self::ALLOWED_ONBOARDING_STEPS, true ) ) {
			$out['onboardingCurrentStep'] = $raw['onboardingCurrentStep'];
		}

		return $out;
	}

	/**
	 * @param array<string, mixed> $raw_rules
	 * @return array<string, mixed>
	 */
	private static function sanitize_regional_rules( array $raw_rules ): array {
		$opt_in  = self::sanitize_country_codes( $raw_rules['optInCountries'] ?? [] );
		$opt_out = self::sanitize_country_codes( $raw_rules['optOutCountries'] ?? [] );
		$no_ban  = self::sanitize_country_codes( $raw_rules['noBannerCountries'] ?? [] );

		$opt_out = array_values( array_diff( $opt_out, $opt_in ) );
		$no_ban  = array_values( array_diff( $no_ban, $opt_in, $opt_out ) );

		$fallback = $raw_rules['fallbackModel'] ?? 'opt-in';
		if ( ! in_array( $fallback, self::ALLOWED_FALLBACK_MODELS, true ) ) {
			$fallback = 'opt-in';
		}

		return [
			'optInCountries'   => $opt_in,
			'optOutCountries'  => $opt_out,
			'noBannerCountries' => $no_ban,
			'fallbackModel'    => $fallback,
		];
	}

	/**
	 * @param mixed $raw
	 * @return string[]
	 */
	private static function sanitize_country_codes( $raw ): array {
		if ( ! is_array( $raw ) ) {
			return [];
		}

		return array_values( array_unique( array_filter(
			array_map(
				static function ( $code ) {
					$code = strtolower( sanitize_text_field( (string) $code ) );
					return preg_match( self::ISO_CODE_PATTERN, $code ) ? $code : null;
				},
				$raw
			)
		) ) );
	}
}
