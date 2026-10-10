import DirectionProvider from '@elementor/ui/DirectionProvider';
import { ThemeProvider } from '@elementor/ui/styles';
import domReady from '@wordpress/dom-ready';
import { Fragment, StrictMode, createRoot, useState } from '@wordpress/element';

import DeactivationDialog from './deactivation-dialog';

let appInstance: { open: () => void } | null = null;

const DeactivationApp = () => {
	const [isOpen, setIsOpen] = useState(false);

	const isRTL = window.cookiezDeactivationData?.isRTL ?? false;
	const isDevelopment = window.cookiezDeactivationData?.isDevelopment ?? false;
	const AppWrapper = isDevelopment ? StrictMode : Fragment;

	appInstance = {
		open: () => setIsOpen(true),
	};

	return (
		<AppWrapper>
			<DirectionProvider rtl={isRTL}>
				<ThemeProvider colorScheme="auto">
					<DeactivationDialog
						isOpen={isOpen}
						onClose={() => setIsOpen(false)}
					/>
				</ThemeProvider>
			</DirectionProvider>
		</AppWrapper>
	);
};

domReady(() => {
	const rootNode = document.getElementById('deactivation-app');
	if (!rootNode) {
		return;
	}

	const root = createRoot(rootNode);
	root.render(<DeactivationApp />);

	const deactivateLink = document.querySelector<HTMLAnchorElement>(
		'tr[data-plugin="cookiez/cookiez.php"] .deactivate a, ' +
			'tr[data-slug="cookiez"] .deactivate a',
	);

	if (deactivateLink) {
		const originalHref = deactivateLink.getAttribute('href') ?? '';

		if (window.cookiezDeactivationData) {
			window.cookiezDeactivationData.deactivateUrl = originalHref;
		}

		deactivateLink.addEventListener('click', (e) => {
			e.preventDefault();
			appInstance?.open();
		});
	}
});
