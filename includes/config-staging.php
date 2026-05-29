<?php
return [
    'plugin-configuration-url' => admin_url( 'admin.php?page=neexa-ai-configuration' ),
    'plugin-home-url'           => admin_url( 'admin.php?page=neexa-ai-home' ),
    'widget-loader-script-url'  => 'https://chat-widget.neexa.ai/dev/main.js',
    'frontend-host'             => 'https://staging.neexa.ai',
    'api-host'                  => 'https://dev-bkd.neexa.ai/api',
    'ajax-url'                  => admin_url( 'admin-ajax.php' ),
    'default-settings'          => [
        'chat_position'   => 'bottom_right',
        'appearance_mode' => 'light',
        'live_status'     => '1',
    ],
    'ai-agent-roles-full-name'  => [
        'salesman'  => 'Sales Assistant',
        'qa-agent'  => 'Inquiry Assistant',
    ],
];
