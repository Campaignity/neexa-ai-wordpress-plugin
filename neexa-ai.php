<?php
if (!headers_sent()) {
	ob_start();
}

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://neexa.co
 * @since             2.0.0
 * @package           Neexa_Ai
 *
 * @wordpress-plugin
 * Plugin Name:       Neexa | Sales AI Agent for B2C Businesses
 * Plugin URI:        https://neexa.co
 * Description:       This plugin seamlessly integrates Neexa.AI's 24/7 AI Powered Sales Agent/Assistant onto any WordPress site.
 * Version:           2.0.0
 * Author:            Neexa
 * Author URI:        https://neexa.co/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       neexa-ai
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define('NEEXA_AI_VERSION', '2.0.0');
define('NEEXA_AI_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-neexa-ai-activator.php
 */
function activate_neexa_ai()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-neexa-ai-activator.php';
	Neexa_Ai_Activator::activate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-neexa-ai-deactivator.php
 */
function deactivate_neexa_ai()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-neexa-ai-deactivator.php';
	Neexa_Ai_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_neexa_ai');
register_deactivation_hook(__FILE__, 'deactivate_neexa_ai');


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require plugin_dir_path(__FILE__) . 'includes/class-neexa-ai.php';
require plugin_dir_path(__FILE__) . 'includes/class-neexa-ai-feedback.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_neexa_ai()
{

	$plugin = new Neexa_Ai();
	$plugin->run();
}
run_neexa_ai();
