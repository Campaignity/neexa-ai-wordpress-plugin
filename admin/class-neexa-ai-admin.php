<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://neexa.co
 * @since      1.0.0
 *
 * @package    Neexa_Ai
 * @subpackage Neexa_Ai/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and enqueuing the 
 * admin-specific stylesheet and JavaScript and other needed items
 *
 * @package    Neexa_Ai
 * @subpackage Neexa_Ai/admin
 * @author     Neexa <hello@neexa.co>
 */
class Neexa_Ai_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/neexa-ai-admin.css', array(), $this->version, 'all');
		wp_enqueue_style('wp-jquery-ui-dialog');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/neexa-ai-admin.js', array('jquery', 'jquery-ui-dialog'), $this->version, false);

		wp_localize_script($this->plugin_name, 'neexa_ai_env_vars', $this->get_global_script_info());
	}

	private function get_global_script_info()
	{
		global $neexa_ai_config;

		/**
		 * about site */
		$current_user = wp_get_current_user();

		$user_info = get_userdata($current_user->ID);

		$about_info = [
			'site'       => [
				'url' =>	get_site_url(),
				'name'      => get_bloginfo('name'),
				'decs' => get_bloginfo('description'),
			],
			'user'        => [
				'first_name' => $user_info->first_name ? $user_info->first_name : $current_user->display_name,
				'last_name' => $user_info->last_name ? $user_info->last_name : "",
				'email' => $user_info->user_email
			],
			'active_agent_id' => get_option('neexa-ai-active-options')['id'] ?? null
		];

		return array_merge(
			$neexa_ai_config,
			[
				'about-info' => $about_info,
				'nonce' => wp_create_nonce('neexa_nonce'),
				'auth-token' => get_option('neexa_ai_access_token'),
			]
		);
	}

	public function activation_welcome()
	{
		if (get_option('do_neexa_ai_activation')) {

			delete_option('do_neexa_ai_activation');

			wp_redirect(admin_url('admin.php?page=neexa-ai-home'));

			exit;
		}
	}

	public function add_plugin_admin_menu()
	{

		add_menu_page(
			'Neexa AI Home',
			'Neexa AI',
			'manage_options',
			'neexa-ai-home',
			function () {
				require_once plugin_dir_path(__FILE__) . 'partials/neexa-ai-dashboard.php';
			},
			plugin_dir_url(__FILE__) . "img/neexa-logomark.png?v=5",
			2
		);

		add_submenu_page(
			'neexa-ai-home',
			'Neexa AI Home',
			'Home',
			'manage_options',
			'neexa-ai-home',
			function () {
				require_once plugin_dir_path(__FILE__) . 'partials/neexa-ai-dashboard.php';
			},
		);

		add_submenu_page(
			'neexa-ai-home',
			'Neexa AI Configuration',
			'Configure',
			'manage_options',
			'neexa-ai-configuration',
			function () {
				require_once plugin_dir_path(__FILE__) . 'partials/neexa-ai-configure.php';
			},
		);

		add_submenu_page(
			'neexa-ai-home',
			'About Neexa AI',
			'How it Works',
			'manage_options',
			'neexa-ai-how-it-works',
			function () {
				require_once plugin_dir_path(__FILE__) . 'partials/neexa-ai-how-it-works.php';
			},
		);

		add_submenu_page(
			'neexa-ai-home',
			'',
			'',
			'manage_options',
			'get-started-with-neexa',
			function () {
				require_once plugin_dir_path(__FILE__) . 'partials/neexa-ai-user-onboarding.php';
			},
		);

		add_submenu_page(
			NEEXA_AI_PLUGIN_BASENAME,
			'Plugin Feedback',
			'',
			'manage_options',
			'neexa-feedback-before-deactivate',
			function () {

				require_once plugin_dir_path(__FILE__) . 'partials/neexa-ai-user-ondeactivation-feedback.php';
			},
		);
	}

	public function save_access_token()
	{
		$access_token = sanitize_text_field($_POST['access_token']);

		if (!empty($access_token)) {

			update_option('neexa_ai_access_token', $access_token);

			wp_send_json_success('Token saved successfully.');
		} else {
			wp_send_json_error('Invalid token.');
		}
	}

	public function save_neexa_ai_deployment()
	{
		$access_token = sanitize_text_field($_POST['access_token']);
		$ai_agent_id = sanitize_text_field($_POST['ai_agent_id']);

		if (!empty($access_token) && !empty($ai_agent_id)) {

			update_option('neexa_ai_access_token', $access_token);
			update_option('neexa-ai-active-options', [
				'id' => $ai_agent_id
			]);

			wp_send_json_success('Values saved successfully.');
		} else {
			wp_send_json_error('Invalid values.');
		}
	}

	public function override_deactivate_link($links)
	{
		if (current_user_can('activate_plugins')) {
			$custom_url = admin_url('admin.php?page=neexa-feedback-before-deactivate');
			foreach ($links as $index => $link) {
				if (strpos($link, 'deactivate') !== false) {
					$links[$index] = '<a href="' . esc_url($custom_url) . '">Deactivate</a>';
					break;
				}
			}
		}
		return $links;
	}

	public function deauthentication()
	{

		global $neexa_ai_config;

		if (isset($_POST['neexa_deauth']) && check_admin_referer('neexa_deauth_action', 'neexa_deauth_nonce')) {

			// delete_option('neexa-ai-options');
			delete_option('neexa_ai_access_token');
			delete_option('neexa-ai-active-options');

			wp_redirect($neexa_ai_config['plugin-home-url'] . '&deauth=1');
			exit;
		}
	}

	public function neexa_admin_notices()
	{
		if (isset($_GET['deauth']) && $_GET['deauth'] == '1') {
			echo '<div class="notice notice-success is-dismissible"><p>✅ Neexa has been disconnected successfully.</p></div>';
		}
	}

	public function register_settings()
	{
		//register our settings
		register_setting(
			'neexa-ai',
			'neexa-ai-options',
			array(
				// 'show_in_rest'      => true,
				'type'              => 'array',
				'sanitize_callback' => function ($input) {

					$current = get_option('neexa-ai-options', []);
					return array_merge(
						$current,
						array(
							'live_status' => isset($input['live_status']) ? sanitize_text_field($input['live_status']) :  "",
							'chat_position'   => isset($input['chat_position']) ? sanitize_text_field($input['chat_position']) : ($current['chat_position'] ?? ""),
							'appearance_mode' => isset($input['appearance_mode']) ? sanitize_text_field($input['appearance_mode']) : ($current['appearance_mode'] ?? ""),
						)
					);
				}
			)
		);

		//register our settings
		register_setting(
			'neexa-ai-active',
			'neexa-ai-active-options',
			array(
				// 'show_in_rest'      => true,
				'type'              => 'array',
				'sanitize_callback' => function ($input) {

					$current = get_option('neexa-ai-options', []);
					return array_merge(
						$current,
						array(
							'id' => isset($input['id']) ? sanitize_text_field($input['id']) :  "",
						)
					);
				}
			)
		);
	}
}
