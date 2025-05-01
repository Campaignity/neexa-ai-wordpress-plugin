<?php

/**
 * Fired during plugin activation
 *
 * @link       https://neexa.co
 * @since      1.0.0
 *
 * @package    Neexa_Ai
 * @subpackage Neexa_Ai/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Neexa_Ai
 * @subpackage Neexa_Ai/includes
 * @author     Neexa <hello@neexa.co>
 */
class Neexa_Ai_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option( 'do_neexa_ai_activation', true );
	}

}
