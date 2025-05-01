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

		let authSuccess = false;

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

					if (!authSuccess) {
						$("#oauth-dialog").dialog("close");
						showError("Login window was closed before authentication.");
					}
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

					authSuccess = true;
					popupWindow && popupWindow.close();

					$("#oauth-dialog").dialog({
						title: "Verifying Login..."
					});

					/* post the token to the backend */
					$.post(window.neexa_ai_env_vars['ajax-url'], {
						action: 'save_neexa_ai_access_token',
						access_token: accessToken
					}, function (response) {
						if (response.success) {
							window.location.href = window.neexa_ai_env_vars['plugin-configuration-url'];
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

			const iframe = document.querySelector('#neexa-ai-onboarding-iframe-container .full-page-iframe');

			if (data.type === "click-action") {
				switch (data.payload['click-name']) {
					case 'logout':
						window.location.href = window.neexa_ai_env_vars['plugin-home-url'];
						break;
				}
			}

			if (data.type === "request-what-we-know") {
				iframe.contentWindow.postMessage({
					type: "what-you-need-to-know",
					payload: window.neexa_ai_env_vars['about-info']
				}, '*');
			}

			if (data.type === "onboarding-done-action") {
				window.location.href = window.neexa_ai_env_vars['plugin-home-url'];
			}

			if (data.type === "deploy-action") {
				var accessToken = data.payload.access_token;
				var aiAgentId = data.payload.agent_id;
				if (accessToken && aiAgentId) {

					/* post the token to the backend */
					$.post(window.neexa_ai_env_vars['ajax-url'], {
						action: 'save_neexa_ai_deployment',
						'ai_agent_id': aiAgentId,
						access_token: accessToken
					}, function (response) {
						if (response.success) {
							iframe.contentWindow.postMessage({
								type: "wordpresss-site-deployment-status",
								payload: {
									status: "success"
								}
							}, '*');
						} else {
							iframe.contentWindow.postMessage({
								type: "wordpresss-site-deployment-status",
								payload: {
									error: response.data
								}
							}, '*');
						}
					});

				}
			}
		}, false);

	};

	/*
	* management tabs switching
	*/
	const agentsManagement = () => {

		// Tab switching functionality
		$(".tab").click(function () {
			// Remove the active class from all tabs and contents
			$(".tab").removeClass("active");
			$(".tab-content").removeClass("active");

			// Add the active class to the clicked tab
			$(this).addClass("active");

			// Show the corresponding content
			$("#" + $(this).attr("id").replace("-tab", "-content")).addClass("active");
		});

		// Save button logic
		const form = document.getElementById("neexa-settings-form");
		const saveBtn = document.getElementById("save-settings-btn");
		const originalValues = {};

		// Store initial states
		form && form.querySelectorAll(".track-change").forEach(input => {
			if (input.type === "radio" || input.type === "checkbox") {
				originalValues[input.name + input.value] = input.checked;
			} else {
				originalValues[input.name] = input.value;
			}
		});

		// Listen for changes
		form && form.querySelectorAll(".track-change").forEach(input => {
			input.addEventListener("change", () => {
				let changed = false;

				form.querySelectorAll(".track-change").forEach(inp => {
					if (inp.type === "radio" || inp.type === "checkbox") {
						const key = inp.name + inp.value;
						if (originalValues[key] !== inp.checked) {
							changed = true;
						}
					} else if (originalValues[inp.name] !== inp.value) {
						changed = true;
					}
				});

				saveBtn.disabled = !changed;
			});
		});
	};

	/*
	* 
	*/
	function openFullScreenChildWindow(url) {
		const screenWidth = screen.width;
		const screenHeight = screen.height;

		const features = `width=${screenWidth},height=${screenHeight},left=0,top=0,` +
			`toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes`;

		const child = window.open(url, 'NeexaPopupWindow', features);
		if (child) {
			child.focus();
		} else {
			alert('Popup blocked! Please allow popups for this site.');
		}

		return child;
	}

	const externalLinksManagement = () => {
		document.querySelectorAll("a.open-in-child").forEach(anchor => {
			anchor.addEventListener("click", function (e) {
				e.preventDefault();

				const nextUrl = anchor.dataset.href;

				const initialUrl = window.neexa_ai_env_vars['frontend-host'] + '#/plugin-auth/next';

				const child = openFullScreenChildWindow(initialUrl);

				// Listen for message from child
				window.addEventListener("message", function handleMessage(event) {

					const allowedOrigin = window.neexa_ai_env_vars['frontend-host'];
					if (event.origin !== allowedOrigin) {
						return;
					}

					const data = event.data;
					if (data.type === "issue-next") {
						child.postMessage(
							{
								type: "next-url",
								payload: {
									next_url: nextUrl,
									token: window.neexa_ai_env_vars['auth-token'],
								}
							},
							window.neexa_ai_env_vars['frontend-host']
						);
					}
				});
			});
		});
	};

	const deactivationForm = () => {
		const modal = document.getElementById("neexa-feedback-modal");
		const otherBox = document.getElementById("neexa-other-textarea");
		const radios = document.querySelectorAll("input[name='neexa_reason']");

		// Show modal immediately on load
		modal && (modal.style.display = "flex");

		radios.forEach(function (radio) {
			radio.addEventListener("change", function () {
				otherBox.style.display = this.value === "other" ? "block" : "none";
			});
		});
	}

	$(function () {
		OAuthHandler();

		agentsManagement();

		deactivationForm();

		onboardingHandler();

		externalLinksManagement();
	});

})(jQuery);
