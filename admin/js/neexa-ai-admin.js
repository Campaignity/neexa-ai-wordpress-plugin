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

			const oauthUrl = `${window.neexa_ai_env_vars['frontend-host']}/#/signin/wordpress`;

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

			const allowedOrigin = window.neexa_ai_env_vars['frontend-host'];
			if (event.origin !== allowedOrigin) {
				return;
			}

			const data = event.data;

			if (data.type === "auth-action") {
				var accessToken = data.payload.access_token;
				if (accessToken) {

					/* post the token to the backend */
					$.post(ajaxurl, {
						action: 'save_neexa_ai_access_token',
						access_token: accessToken
					}, function (response) {
						if (response.success) {
							window.location.href = window.neexa_ai_env_vars['plugin-home-url'];
							$("#oauth-dialog").dialog("close");
						} else {
							showError("Failed to save authentication information.");
						}
					});

				} else {
					$("#oauth-dialog").dialog("close");
					showError("Authentication failed.");
				}
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
			const allowedOrigin = window.neexa_ai_env_vars['frontend-host'];
			if (event.origin !== allowedOrigin) {
				return;
			}

			const data = event.data;

			if (data.type === "click-action") {
				switch (data.payload['click-name']) {
					case 'logout':
						window.location.href = window.neexa_ai_env_vars['plugin-home-url'];
						break;
				}
			}

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
