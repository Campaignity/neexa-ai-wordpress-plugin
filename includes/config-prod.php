<?php
return [
    // 'plugin-configuration-url' => admin_url('admin.php?page=neexa-ai-configuration'),
    // 'widget-loader-script-url'=>'https://chat-widget.neexa.ai/main.js',   
    // 'plugin-home-url' => admin_url('admin.php?page=neexa-ai-home'),    
    // 'frontend-host' => 'https://app.neexa.ai',
    // 'api-host' => 'https://bkd-v2.neexa.ai/api',
    // 'ajax-url' => admin_url('admin-ajax.php'),
    // 'default-settings' => array(
    //     'chat_position'   => 'bottom_right',
    //     'appearance_mode' => 'light',
    //     'live_status' => '1',
    // ),
    // 'ai-agent-roles-full-name' => array(
    //     'salesman' => "Sales Assistant",
    //     "qa-agent" => "Inquiry Assistant"
    // ),


    'plugin-configuration-url' => admin_url('admin.php?page=neexa-ai-configuration'),
    'widget-loader-script-url'=>'https://chat-widget.neexa.ai/dev/main.js',    
    'plugin-home-url' => admin_url('admin.php?page=neexa-ai-home'),    
    'frontend-host' => 'https://staging.neexa.ai',
    'api-host' => 'https://dev-bkd.neexa.ai/api/',
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