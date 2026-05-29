<?php
// Copy this file to config-local.php and fill in your local values.
// Then add  define( 'NEEXA_ENV', 'local' );  to your wp-config.php.
return [
    'plugin-configuration-url' => admin_url( 'admin.php?page=neexa-ai-configuration' ),
    'plugin-home-url'           => admin_url( 'admin.php?page=neexa-ai-home' ),
    'widget-loader-script-url'  => 'https://chat-widget.neexa.ai/dev/main.js',
    'frontend-host'             => 'https://localhost:8089',
    'api-host'                  => 'http://localhost:8000/api/',
    'ajax-url'                  => admin_url( 'admin-ajax.php' ),
    'default-settings'          => [
        'chat_position'     => 'bottom_right',
        'appearance_mode'   => 'light',
        'mobile_mini_style' => 'greeting_only',
        'default_visibility'=> 'open',
        'is_hide_and_seek'  => '',
        'hide_offset'       => '20',
        'live_status'       => '1',
    ],
    'ai-agent-roles-full-name'  => [
        'salesman'  => 'Sales Assistant',
        'qa-agent'  => 'Inquiry Assistant',
    ],
];
