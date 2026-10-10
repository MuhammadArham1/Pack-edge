import XIcon from '@elementor/icons/XIcon';
import Button from '@elementor/ui/Button';
import Dialog from '@elementor/ui/Dialog';
import DialogActions from '@elementor/ui/DialogActions';
import DialogContent from '@elementor/ui/DialogContent';
import DialogTitle from '@elementor/ui/DialogTitle';
import FormControlLabel from '@elementor/ui/FormControlLabel';
import IconButton from '@elementor/ui/IconButton';
import Radio from '@elementor/ui/Radio';
import RadioGroup from '@elementor/ui/RadioGroup';
import TextField from '@elementor/ui/TextField';
import Typography from '@elementor/ui/Typography';
import { styled } from '@elementor/ui/styles';
import { mixpanelEvents, mixpanelService } from '@cookiez/globals';
import {
	PLUGIN_DEACTIVATED_BASE_PROPS,
	PLUGIN_DEACTIVATED_SKIP_DESC,
	PLUGIN_DEACTIVATED_SUBMIT_DESC,
} from '@cookiez/globals/services/mixpanel/mixpanel-props';
import { Fragment, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { sendFeedback } from './api';
import { DETAIL_LABELS, DETAIL_PLACEHOLDERS, REASON_LABELS } from './constants';
import {
	DeactivationReason,
	REASONS_WITH_DETAILS,
	REASONS_WITH_TEXTAREA,
	type DeactivationDialogProps,
} from './types';

const DeactivationDialog = ({ isOpen, onClose }: DeactivationDialogProps) => {
	const [selectedReason, setSelectedReason] =
		useState<DeactivationReason | null>(null);
	const [additionalFeedback, setAdditionalFeedback] = useState('');
	const [isSubmitting, setIsSubmitting] = useState(false);

	const deactivateUrl = window.cookiezDeactivationData?.deactivateUrl ?? '';

	const handleDeactivate = () => {
		if (deactivateUrl) {
			window.location.href = deactivateUrl;
		}
	};

	const handleSubmit = async () => {
		if (selectedReason) {
			setIsSubmitting(true);
		}

		mixpanelService.sendEvent(mixpanelEvents.pluginDeactivated, {
			...PLUGIN_DEACTIVATED_BASE_PROPS,
			target_value: 'submit_and_deactivate',
			interaction_desc: PLUGIN_DEACTIVATED_SUBMIT_DESC,
			plan_type: window.cookiezDeactivationData?.planData?.plan?.name,
			reason: selectedReason ?? null,
		});

		if (selectedReason) {
			try {
				await sendFeedback({
					reason: selectedReason,
					additional_data: additionalFeedback || undefined,
				});
			} catch {
				// Continue with deactivation even if feedback fails
			}
		}

		handleDeactivate();
	};

	const handleSkip = () => {
		handleDeactivate();
		mixpanelService.sendEvent(mixpanelEvents.pluginDeactivated, {
			...PLUGIN_DEACTIVATED_BASE_PROPS,
			target_value: 'skip_and_deactivate',
			interaction_desc: PLUGIN_DEACTIVATED_SKIP_DESC,
			plan_type: window.cookiezDeactivationData?.planData?.plan?.name,
		});
	};

	return (
		<Dialog open={isOpen} onClose={onClose} maxWidth="sm" fullWidth>
			<StyledDialogTitle>
				<Typography variant="h6" color="text.primary">
					{__('Quick Feedback', 'cookiez')}
				</Typography>
				<CloseButton
					size="small"
					onClick={onClose}
					aria-label={__('Close', 'cookiez')}
				>
					<XIcon />
				</CloseButton>
			</StyledDialogTitle>

			<DialogContent>
				<Typography
					variant="body2"
					color="text.secondary"
					sx={{ marginBlockEnd: 2 }}
				>
					{__(
						'If you have a moment, please share why you are deactivating Cookie Consent:',
						'cookiez',
					)}
				</Typography>

				<RadioGroup
					value={selectedReason ?? ''}
					onChange={(e) =>
						setSelectedReason(e.target.value as DeactivationReason)
					}
				>
					{Object.values(DeactivationReason).map((reason) => {
						const showTextField =
							selectedReason === reason &&
							REASONS_WITH_DETAILS.includes(reason);
						const isTextarea = REASONS_WITH_TEXTAREA.includes(reason);

						return (
							<Fragment key={reason}>
								<StyledFormControlLabel
									value={reason}
									control={<Radio size="small" />}
									label={REASON_LABELS[reason]}
								/>
								{showTextField && (
									<TextFieldWrapper>
										<DetailLabel>{DETAIL_LABELS[reason]}</DetailLabel>
										{isTextarea ? (
											<StyledTextarea
												rows={3}
												placeholder={DETAIL_PLACEHOLDERS[reason]}
												value={additionalFeedback}
												onChange={(e) => setAdditionalFeedback(e.target.value)}
											/>
										) : (
											<StyledTextField
												fullWidth
												placeholder={DETAIL_PLACEHOLDERS[reason]}
												value={additionalFeedback}
												onChange={(e) => setAdditionalFeedback(e.target.value)}
												size="small"
											/>
										)}
									</TextFieldWrapper>
								)}
							</Fragment>
						);
					})}
				</RadioGroup>
			</DialogContent>

			<DialogActions
				sx={{
					justifyContent: 'space-between',
					paddingInline: 3,
					paddingBlockEnd: 2,
				}}
			>
				<Button
					variant="text"
					color="inherit"
					onClick={handleSkip}
					disabled={isSubmitting}
				>
					{__('Skip & Deactivate', 'cookiez')}
				</Button>
				<Button
					variant="contained"
					onClick={handleSubmit}
					disabled={isSubmitting}
				>
					{isSubmitting
						? __('Submitting…', 'cookiez')
						: __('Submit & Deactivate', 'cookiez')}
				</Button>
			</DialogActions>
		</Dialog>
	);
};

const StyledFormControlLabel = styled(FormControlLabel)`
	margin-inline-start: 0;
	margin-block-end: ${({ theme }) => theme.spacing(0.5)};
`;

const TextFieldWrapper = styled('div')`
	padding-inline-start: ${({ theme }) => theme.spacing(4.75)};
	margin-block-end: ${({ theme }) => theme.spacing(1)};
`;

const StyledTextarea = styled('textarea')`
	width: 100%;
	padding: ${({ theme }) => theme.spacing(1)};
	font-size: 14px;
	border: 1px solid ${({ theme }) => theme.palette.text.secondary};
	border-radius: ${({ theme }) => theme.shape.borderRadius}px;
	resize: vertical;
	font-family: inherit;
	min-height: 78px;
	outline: none;
	color: ${({ theme }) => theme.palette.text.primary};
	background-color: initial;

	&:focus {
		border-color: ${({ theme }) => theme.palette.primary.main};
		box-shadow: none;
		outline: 1px solid ${({ theme }) => theme.palette.primary.main};
	}
`;

const StyledTextField = styled(TextField)`
	input[type='text'] {
		color: ${({ theme }) => theme.palette.text.primary};
		background-color: initial;
		width: 100%;
		font-size: 14px;
		border: 1px solid ${({ theme }) => theme.palette.text.secondary};
		border-radius: ${({ theme }) => theme.shape.borderRadius}px;
		font-family: inherit;

		&:focus {
			border-color: ${({ theme }) => theme.palette.primary.main};
			box-shadow: none;
			outline: none;
		}
	}
`;

const DetailLabel = styled(Typography)`
	font-size: 12px;
	color: ${({ theme }) => theme.palette.text.secondary};
	margin-block-end: ${({ theme }) => theme.spacing(0.5)};
`;

const StyledDialogTitle = styled(DialogTitle)`
	position: relative;
	padding-inline-end: ${({ theme }) => theme.spacing(6)};
`;

const CloseButton = styled(IconButton)`
	position: absolute;
	inset-inline-end: ${({ theme }) => theme.spacing(1)};
	inset-block-start: ${({ theme }) => theme.spacing(1)};
`;

export default DeactivationDialog;
