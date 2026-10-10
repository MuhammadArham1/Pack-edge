import ScreenManager from '@cookiez/banner/core/screen-manager';
import type { CookieItem } from '@cookiez/globals/types/cookie-types';
import type { PlanData } from '@cookiez/globals/types/plan-data-types';
import type { IntegrationsState } from '@cookiez/globals/types/settings-types';

export {};

declare global {
	type Environment = 'production' | 'development';

	interface Window {
		cookiezSettingsData?: {
			isConnected: boolean;
			isUrlMismatch: boolean;
			appSlug: string;
			appVersion: string;
			wpVersion: string;
			isDevelopment: boolean;
			isElementorOne: boolean;
			hasElementorOneSubscription: boolean;
			isRTL: boolean;
			pluginEnv: Environment;
			restRoot: string;
			settings?: Record<string, unknown>;
			maxmindLicenseKey?: string;
			content?: Record<string, unknown>;
			wpRestNonce: string;
			planData?: PlanData;
			planScope?: string[];
			dateFormat: string;
			timeFormat: string;
			translations?: Record<string, string>;
			isElementorActive: boolean;
			isElementorProActive: boolean;
			elementorCookieConsentId?: number;
			elementorPreferencesBannerId?: number;
			integrations?: IntegrationsState;
		};
		cookiezBannerSettings?: {
			isDevelopment: boolean;
			isRTL: boolean;
			language?: string;
			publicApiKey?: string;
			url?: string;
			serviceUrl?: string;
			settings: Record<string, unknown>;
			content?: Record<string, unknown>;
			cookies?: CookieItem[];
			translations?: Record<string, string>;
			cookiesHash?: string;
			elementorCookieConsentId?: number;
			elementorPreferencesBannerId?: number;
			integrations?: IntegrationsState;
			geoEndpoint?: string;
			wpRestNonce?: string;
		};
		cookiezBanner?: {
			screenManager?: ScreenManager;
			[key: string]: unknown;
		};
		cookiezReviewData?: {
			wpRestNonce: string;
			reviewData?: Record<string, unknown>;
			isRTL: boolean;
			isDevelopment?: boolean;
		};
		cookiezDeactivationData?: {
			wpRestNonce: string;
			deactivateUrl: string;
			isRTL: boolean;
			isDevelopment?: boolean;
			pluginEnv?: Environment;
			appVersion?: string;
			wpVersion?: string;
			planData?: PlanData;
			planScope?: string[];
		};
		elementorFrontend?: {
			documentsManager?: {
				documents?: Record<number, { getModal?: () => unknown }>;
			};
		};
		elementorProFrontend?: {
			modules?: {
				popup?: {
					showPopup?: (args: { id: number }) => void;
					closePopup?: (
						settings: Record<string, unknown>,
						event: Event,
					) => void;
				};
			};
		};
		gtag: (...args: unknown[]) => void;
		wp_consent_type?: string;
		wp_set_consent?: (category: string, status: 'allow' | 'deny') => void;
	}
}
