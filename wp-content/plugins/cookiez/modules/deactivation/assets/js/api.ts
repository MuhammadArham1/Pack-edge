import { APIBase } from '@cookiez/globals';

import { V1_PREFIX } from './constants';
import { feedbackSchema, type FeedbackPayload } from './types';

export const sendFeedback = async (data: FeedbackPayload): Promise<unknown> => {
	const payload = feedbackSchema.parse(data);
	return APIBase.request({
		method: 'POST',
		path: `${V1_PREFIX}/deactivation/feedback`,
		data: payload,
	});
};
