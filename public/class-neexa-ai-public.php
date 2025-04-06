<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://neexa.co
 * @since      1.0.0
 *
 * @package    Neexa_Ai
 * @subpackage Neexa_Ai/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Neexa_Ai
 * @subpackage Neexa_Ai/public
 * @author     Neexa <hello@neexa.co>
 */
class Neexa_Ai_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Neexa_Ai_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Neexa_Ai_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/neexa-ai-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Neexa_Ai_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Neexa_Ai_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/neexa-ai-public.js', array( 'jquery' ), $this->version, false );

		$neexa_ai_agents_configs = get_option('neexa_ai_agents_configs');

		if (!empty($neexa_ai_agents_configs["config_agent_id"])) {
	
			wp_enqueue_script(
				"cam_neexai_agent_id",
				"https://chat-widget.neexa.ai/main.js",
				[],
				time(),
				[
					"in_footer" => false
				]
			);
	
			wp_add_inline_script(
				"cam_neexai_agent_id",
				'var neexa_xgmx_cc_wpq_ms = "' . esc_html($neexa_ai_agents_configs["config_agent_id"]) . '";',
				"before"
			);
		}

	}

}
