(function ($) {
	'use strict';

	/**
	 * All of the code for the admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed jQuery code will be written here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables us to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */


	/* OAuth-like access token acquiring */
	const OAuthHandler = () => {
		let popupWindow;

		// Open OAuth in Popup and show jQuery UI Dialog
		$('#oauth-login-btn').on('click', function (e) {
			e.preventDefault();

			const oauthUrl = "<?php echo esc_url($your_identity_provider_oauth_url); ?>";
			const w = 600, h = 700;
			// Fixes dual-screen position                             Most browsers      Firefox
			const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
			const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

			const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
			const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

			const systemZoom = width / window.screen.availWidth;
			const left = (width - w) / 2 / systemZoom + dualScreenLeft
			const top = (height - h) / 2 / systemZoom + dualScreenTop

			popupWindow = window.open(
				oauthUrl,
				"OAuth Login",
				`width=${w / systemZoom},height=${h / systemZoom},top=${top},left=${left},scrollbars=yes,resizable=yes`
			);

			// Show Dialog
			$("#oauth-dialog").dialog({
				'dialogClass': 'wp-dialog',
				modal: true,
				width: 400,
				closeOnEscape: false,
				open: function () {
					$(".ui-dialog-titlebar-close").hide(); // Disable close button
				},
				buttons: {
					Cancel: function () {
						popupWindow && popupWindow.close();

						$(this).dialog("close");
						showError("Login was cancelled.");
					}
				}
			});

			// Check if popup closes without auth
			const popupChecker = setInterval(function () {
				if (popupWindow && popupWindow.closed) {
					clearInterval(popupChecker);
					$("#oauth-dialog").dialog("close");
					showError("Login window was closed before authentication.");
				}
			}, 500);
		});

		// Listen for postMessage from the Identity Provider
		window.addEventListener("message", function (event) {

			if (event.origin !== "<?php echo esc_js($your_identity_provider_origin); ?>") return;

			if (event.data.status === "success") {
				$("#oauth-dialog").dialog("close");
				//!returns token, save it in db
				//!test doing some fetch, if everything is okay, redirect to neexa-ai=home
				location.reload(); // or update UI if needed
			} else {
				$("#oauth-dialog").dialog("close");
				showError("Authentication failed.");
			}
		}, false);

		function showError(msg) {
			$('<div>')
				.text(msg)
				.dialog({
					'dialogClass': 'wp-dialog',
					title: "Authentication Error",
					modal: true,
					buttons: {
						OK: function () {
							$(this).dialog("close");
						}
					}
				});
		}
	}

	/*
	* onboarding communication between parent and child
	*/
	const onboardingHandler = () => {

		window.addEventListener("message", function (event) {
			// SECURITY: Only allow messages from the correct origin
			const allowedOrigin = window.neexa_ai_env_vars['frontend-url'];
			if (event.origin !== allowedOrigin) {
				return;
			}

			// Handle the message from the iframe
			const data = event.data;

			// its a click action
			if (data.type === "click-action") {
				switch (data.payload['click-name']) {
					case 'logout':
						window.location.href = this.window.neexa_ai_env_vars['plugin-home-url'];
						break;
				}
			}

			// its a request for data
			if (data.type === "request-what-we-know") {
				const iframe = document.querySelector('#neexa-ai-onboarding-iframe-container .full-page-iframe');
				iframe.contentWindow.postMessage({
					type: "what-you-need-to-know",
					payload: window.neexa_ai_env_vars['about-info']
				}, '*');
			}
		}, false);

	};

	$(function () {
		OAuthHandler();

		onboardingHandler();
	});

})(jQuery);
