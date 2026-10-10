import RegionalConsentModel from '@cookiez/globals/enums/regional-consent-model';
import TemplateType from '@cookiez/globals/enums/template-type';
import type OnboardingStep from '@cookiez/globals/enums/onboarding-step';

export { RegionalConsentModel, TemplateType };

export type GeoTargeting = 'worldwide' | 'adaptive';

export type RegionalRules = {
	optInCountries: string[];
	optOutCountries: string[];
	noBannerCountries: string[];
	fallbackModel: RegionalConsentModel;
};

export type IntegrationsState = {
	wpConsentApiActive: boolean;
	siteKitActive: boolean;
	siteKitConsentMode: boolean;
	delegateGcmToSiteKit: boolean;
	wpConsentApiInstallUrl: string;
	wpConsentApiLearnMoreUrl: string;
};

export type SettingsState = {
	bannerDisplayStatus: boolean;
	disableBannerPages: string[];
	templateType: TemplateType;
	geoTargeting: GeoTargeting;
	regionalRules: RegionalRules;
	regionalRulesAlertDismissed: boolean;
	consentExpiration: number;
	gpcDntSupport: boolean;
	supportGcm: boolean;
	googleTagsBeforeConsent: boolean;
	isOnboardingCompleted: boolean;
	onboardingCurrentStep: OnboardingStep;
	designBannerInfotipDismissed: boolean;
	integrations?: IntegrationsState;
	isMigrationPopupDismissed: boolean;
};
