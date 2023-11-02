<?php
// If uninstall/delete not called from WordPress then exit
if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

// Delete option from options table
delete_option('neexa_ai_agents_configs');
// Delete any other options, custom tables/data, files
