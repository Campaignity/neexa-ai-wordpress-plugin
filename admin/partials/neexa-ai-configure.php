<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://neexa.co
 * @since      1.0.0
 *
 * @package    Neexa_Ai
 * @subpackage Neexa_Ai/admin/partials
 */
?>

<?php

global $neexa_ai_config;

$isAuthenticated = true;

if (1 || get_option('neexa_ai_access_token') && get_option('neexa_ai_access_token') != '') {

    $accessToken = get_option('neexa_ai_access_token');

    $is_agent_live = true; // Example flag (Set this based on the live agent status)
    $agent_avatar_url = 'https://via.placeholder.com/50'; // Replace with actual agent avatar URL
    $whatsapp_configured = true; // Replace with actual condition
    $email_configured = true; // Replace with actual condition
    $instagram_configured = false; // Replace with actual condition
}

if (!$isAuthenticated) {
    $getStartedExplainer = "configure, connect";
    require_once plugin_dir_path(__FILE__) . 'neexa-ai-get-started.php';
    exit();
}
?>

<!-- TAB STYLES -->
<style>
    .plugin-tab-wrapper {
        margin-top: 20px;
    }

    .plugin-tabs {
        display: flex;
        border-bottom: 1px solid #ccc;
    }

    .plugin-tab {
        padding: 10px 20px;
        cursor: pointer;
        background: #f1f1f1;
        border: 1px solid #ccc;
        border-bottom: none;
        margin-right: 5px;
        border-radius: 5px 5px 0 0;
    }

    .plugin-tab.active {
        background: #fff;
        font-weight: bold;
    }

    .plugin-tab-content {
        display: none;
        padding: 20px;
        border: 1px solid #ccc;
        border-top: none;
        background: #fff;
        border-radius: 0 0 5px 5px;
    }

    .plugin-tab-content.active {
        display: block;
    }
</style>

<!-- TAB HTML -->
<div class="plugin-tab-wrapper">
    <div class="plugin-tabs">
        <div class="plugin-tab tab" id="tab1-tab">AI Agent</div>
        <div class="plugin-tab tab active" id="tab2-tab">General Settings</div>
    </div>

    <div class="plugin-tab-content tab-content" id="tab1-content">
        <p>Here are your general settings...</p>
        <!-- Insert form fields or content here -->
    </div>

    <div class="plugin-tab-content tab-content active" id="tab2-content">
        <!-- MATERIAL DESIGN STYLES -->
        <style>
            .material-setting-group {
                margin-bottom: 30px;
            }

            .material-setting-label {
                font-size: 14px;
                font-weight: 600;
                color: #333;
                margin-bottom: 10px;
                display: block;
            }

            .material-radio-group {
                display: flex;
                gap: 20px;
                flex-wrap: wrap;
            }

            .material-radio {
                position: relative;
                display: flex;
                align-items: center;
                cursor: pointer;
                padding-left: 30px;
                font-size: 14px;
                user-select: none;
                color: #444;
            }

            .material-radio input {
                position: absolute;
                opacity: 0;
                cursor: pointer;
            }

            .material-radio .checkmark {
                position: absolute;
                left: 0;
                top: 2px;
                height: 18px;
                width: 18px;
                background-color: #e0e0e0;
                border-radius: 50%;
                transition: background 0.3s;
            }

            .material-radio:hover input~.checkmark {
                background-color: #ccc;
            }

            .material-radio input:checked~.checkmark {
                background-color: #3f51b5;
            }

            .material-radio .checkmark:after {
                content: "";
                position: absolute;
                display: none;
            }

            .material-radio input:checked~.checkmark:after {
                display: block;
            }

            .material-radio .checkmark:after {
                top: 5px;
                left: 5px;
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: white;
            }

            /* .material-save-button {
                background-color: #3f51b5;
                color: #fff;
                border: none;
                padding: 12px 24px;
                border-radius: 6px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.3s;
                margin-top: 10px;
            }

            .material-save-button:hover {
                background-color: #303f9f;
            } */

            button:disabled,
            button[disabled] {
                opacity: 0.5;
                cursor: not-allowed;
            }
        </style>

        <!-- FORM WRAPPER -->
        <form method="post" action="options.php" id="neexa-settings-form">
            <?php settings_fields('neexa-ai'); ?>
            <?php $options = array_merge($neexa_ai_config['default-settings'], get_option('neexa-ai-options', array())) ?>

            <!-- CHAT POSITION -->
            <div class="material-setting-group">
                <label class="material-setting-label">Chat Widget Position</label>
                <div class="material-radio-group">
                    <?php
                    $positions = [
                        'bottom_left'   => 'Bottom Left',
                        'bottom_center' => 'Bottom Middle',
                        'bottom_right'  => 'Bottom Right',
                    ];
                    foreach ($positions as $value => $label) :
                    ?>
                        <label class="material-radio">
                            <input class="track-change" type="radio" name="neexa-ai-options[chat_position]" value="<?php echo esc_attr($value); ?>" <?php checked($options['chat_position'] ?? '', $value); ?>>
                            <span class="checkmark"></span>
                            <?php echo esc_html($label); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- APPEARANCE MODE -->
            <div class="material-setting-group">
                <label class="material-setting-label">Appearance Mode</label>
                <div class="material-radio-group">
                    <?php
                    $modes = [
                        'light' => 'Light Mode',
                        'dark'  => 'Dark Mode',
                    ];
                    foreach ($modes as $value => $label) :
                    ?>
                        <label class="material-radio">
                            <input class="track-change" type="radio" name="neexa-ai-options[appearance_mode]" value="<?php echo esc_attr($value); ?>" <?php checked($options['appearance_mode'] ?? '', $value); ?>>
                            <span class="checkmark"></span>
                            <?php echo esc_html($label); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- SAVE BUTTON -->
            <button type="submit" class="button button-primary" id="save-settings-btn" disabled>Save Settings</button>
        </form>
    </div>
</div>