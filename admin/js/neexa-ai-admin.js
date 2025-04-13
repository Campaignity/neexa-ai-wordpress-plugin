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
					$.post(ajaxurl, {
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


	const agentsManagement = () => {
		let currentPage = 1;
		let agents = [];

		function loadAgents(page = 1) {
			$.get({
				url: window.neexa_ai_env_vars['ajax-url'],
				data: {
					action: 'neexa_fetch_agents',
					page: page
				},
				success: function (response) {
					if (response.success) {
						agents = response.data.agents || [];
						const $dropdown = $('#neexa-agent-dropdown');
						$dropdown.empty();
						agents.forEach(agent => {
							$dropdown.append(`<option value="${agent.id}" data-name="${agent.name}">${agent.name}</option>`);
						});
						$('#agent-status, #agent-links').hide();
					} else {
						alert("Failed to fetch agents.");
					}
				}
			});
		}

		function updateLinks(agentId) {
			const dashboardUrl = `${window.neexa_ai_env_vars['frontend-host']}/agent/${agentId}`;
			$('#link-dashboard').attr('href', `${dashboardUrl}`);
			$('#link-avatar').attr('href', `${dashboardUrl}/avatar`);
			$('#link-training').attr('href', `${dashboardUrl}/training`);
		}

		$('#neexa-agent-dropdown').on('change', function () {
			const agentId = $(this).val();
			const agentName = $(this).find('option:selected').data('name');

			$('#selected-agent-name').text(agentName);
			$('#agent-status, #agent-links').show();
			updateLinks(agentId);
		});

		$('#prev-page').click(function () {
			if (currentPage > 1) {
				currentPage--;
				loadAgents(currentPage);
			}
		});

		$('#next-page').click(function () {
			currentPage++;
			loadAgents(currentPage);
		});

		// Initial load
		loadAgents(currentPage);


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


		// save button state
		const form = document.getElementById("neexa-settings-form");
		const saveBtn = document.getElementById("save-settings-btn");
		const originalValues = {};

		// Store original selected values
		form.querySelectorAll(".track-change").forEach(input => {
			if (input.checked) {
				originalValues[input.name] = input.value;
			}
		});

		// Watch for changes
		form.querySelectorAll(".track-change").forEach(input => {
			input.addEventListener("change", () => {
				let changed = false;
				form.querySelectorAll(".track-change").forEach(inp => {
					if (inp.checked && originalValues[inp.name] !== inp.value) {
						changed = true;
					}
				});

				saveBtn.disabled = !changed;
			});
		});

	};


	$(function () {
		OAuthHandler();

		agentsManagement();

		onboardingHandler();
	});

})(jQuery);
