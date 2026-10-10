import { z } from 'zod';

export enum DeactivationReason {
	NoLongerNeeded = 'no_longer_needed',
	TooExpensive = 'too_expensive',
	DidNotWork = 'did_not_work',
	UnclearHowToUse = 'unclear_how_to_use',
	TechnicalIssues = 'technical_issues',
	SwitchedSolution = 'switched_solution',
	Other = 'other',
}

export const REASONS_WITH_DETAILS: DeactivationReason[] = [
	DeactivationReason.UnclearHowToUse,
	DeactivationReason.SwitchedSolution,
	DeactivationReason.Other,
];

export const REASONS_WITH_TEXTAREA: DeactivationReason[] = [
	DeactivationReason.UnclearHowToUse,
	DeactivationReason.Other,
];

export const feedbackSchema = z.object({
	reason: z.string(),
	additional_data: z.string().optional(),
});

export type FeedbackPayload = z.infer<typeof feedbackSchema>;

export type DeactivationDialogProps = {
	isOpen: boolean;
	onClose: () => void;
};
