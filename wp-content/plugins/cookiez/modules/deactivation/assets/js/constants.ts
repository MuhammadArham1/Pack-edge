import { __ } from '@wordpress/i18n';

import { DeactivationReason } from './types';

export const V1_PREFIX = '/cookiez/v1';

export const REASON_LABELS: Record<DeactivationReason, string> = {
	[DeactivationReason.NoLongerNeeded]: __(
		'I no longer need this plugin',
		'cookiez',
	),
	[DeactivationReason.TooExpensive]: __("It's too expensive", 'cookiez'),
	[DeactivationReason.DidNotWork]: __(
		"The plugin didn't provide the results I was hoping for",
		'cookiez',
	),
	[DeactivationReason.UnclearHowToUse]: __(
		"I wasn't sure how to use the plugin",
		'cookiez',
	),
	[DeactivationReason.TechnicalIssues]: __(
		'I had technical issues or conflicts with my site',
		'cookiez',
	),
	[DeactivationReason.SwitchedSolution]: __(
		'I switched to a different solution',
		'cookiez',
	),
	[DeactivationReason.Other]: __('Other', 'cookiez'),
};

export const DETAIL_LABELS: Partial<Record<DeactivationReason, string>> = {
	[DeactivationReason.UnclearHowToUse]: __(
		'Optional: Was anything unclear or confusing?',
		'cookiez',
	),
	[DeactivationReason.SwitchedSolution]: __(
		'Optional: Please share which solution:',
		'cookiez',
	),
	[DeactivationReason.Other]: __(
		'Optional: Please share the reason:',
		'cookiez',
	),
};

export const DETAIL_PLACEHOLDERS: Partial<Record<DeactivationReason, string>> =
	{
		[DeactivationReason.UnclearHowToUse]: __(
			'Please share details…',
			'cookiez',
		),
		[DeactivationReason.SwitchedSolution]: __('Solution name…', 'cookiez'),
		[DeactivationReason.Other]: __('Please explain…', 'cookiez'),
	};
