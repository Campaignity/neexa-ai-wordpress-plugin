<?php
// copy this and rename to config.php to access configs globally
return [
    'plugin-configuration-url' => admin_url('admin.php?page=neexa-ai-configuration'),
    'widget-loader-script-url'=>'https://chat-widget.neexa.ai/main.js',
    'plugin-home-url' => admin_url('admin.php?page=neexa-ai-home'),    
    'frontend-host' => 'https://localhost:8089',
    'api-host' => 'http://localhost:8000/api/',
    'ajax-url' => admin_url('admin-ajax.php'),
    'default-settings' => array(
        'chat_position'   => 'bottom_right',
        'appearance_mode' => 'light',
        'live_status' => '1',
    ),
    'ai-agent-roles-full-name' => array(
        'salesman' => "Sales Assistant",
        "qa-agent" => "Inquiry Assistant"
    ),
];
