<?php
/*
Plugin Name: Neexa AI
Plugin URI:  https://github.com/Campaignity/neexa-ai-wordpress-plugin
Description: This plugin seamlessly integrates Neexa AI's 24/7 AI Powered Sales Assistants onto any WordPress site
Version: 1.0
Author: Campaignity's Neexa AI
Author URI: https://neexa.ai
License: GPLv2 or later
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly 


add_action('wp_enqueue_scripts', 'cam_neexai_add_header_script');
function cam_neexai_add_header_script()
{
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



// create custom plugin settings menu
add_action('admin_menu', 'cam_neexai_create_menu');
function cam_neexai_create_menu()
{
    //create new top-level menu
    add_menu_page(
        'Neexa AI Assistants Configuration',
        'Neexa AI',
        'manage_options',
        'neexa-ai-agents-for-wordpress-settings',
        'cam_neexai_settings_page',
        NULL,
        2
    );

    add_submenu_page(
        'neexa-ai-agents-for-wordpress-settings',
        'About Neexa AI',
        'How it Works',
        'manage_options',
        'neexa-ai-agents-sub-how-it-works',
        'cam_neexai_how_it_works_page'
    );

    //call register settings function
    add_action('admin_init', 'cam_neexai_register_settings');
}

function cam_neexai_register_settings()
{
    //register our settings
    register_setting(
        'neexa-ai-agents-config-group',
        'neexa_ai_agents_configs',
        'neexa_ai_agents_sanitize_configs'
    );
}

function cam_neexai_sanitize_configs($input)
{
    $input['config_agent_id'] = sanitize_text_field($input['config_agent_id']);
    return $input;
}

function cam_neexai_settings_page()
{
?>
    <div class="wrap">
        <h2>Neexa AI Assistant Configuration</h2>
        <form method="post" action="options.php">
            <?php settings_fields('neexa-ai-agents-config-group'); ?>
            <?php $neexa_ai_agents_configs = get_option('neexa_ai_agents_configs'); ?>


            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Assistant ID</th>
                    <td><input type="text" name="neexa_ai_agents_configs[config_agent_id]" value="<?php echo esc_html(
                                                                                                        !empty($neexa_ai_agents_configs['config_agent_id'])
                                                                                                            ? $neexa_ai_agents_configs['config_agent_id']
                                                                                                            : ""
                                                                                                    ); ?>" /></td>
                </tr>
            </table>
            <i>The Assistant ID can be got from the <a href="http://app.neexa.ai" target="_blank" rel="noopener noreferrer">neexa.ai dashboard</a></i>

            <p class="submit">
                <input type="submit" class="button-primary" value="Save Changes" />
            </p>
        </form>

        <div>
            Need Help Setting Up an AI Assistant? <a href="https://wa.me/256743665790" target="_blank">
                CONTACT US NOW
            </a> to support you.
        </div>
    </div>
<?php
}


function cam_neexai_how_it_works_page()
{
?>
    <div class="wrap">
        <h2>How It Works</h2>

        <h3>1. Create an Account</h3>
        <p>
            To get started with our AI service, you'll need to create an account at <a href="https://app.neexa.ai/#signup" target="_blank">app.neexa.ai/#signup</a>. Follow the registration process and fill in the required information.
        </p>

        <h3>2. Create a New AI Assistant</h3>
        <p>
            Once you've successfully created your account, you can proceed to create a new AI agent by following these steps:
        </p>
        <ol>
            <li>Log in to your Neexa account.</li>
            <li>Navigate to your account dashboard.</li>
            <li>Click on "Create New AI Assistant."</li>
            <li>Give your agent a name and configure any other desired settings.</li>
        </ol>

        <h3>3. Copy the Assistant ID</h3>
        <p>
            You'll need to copy the Assistant ID associated with your AI agent. Here's how:
        </p>
        <ol>
            <li>Access the "Assistant Edit Page" within your Neexa account.</li>
            <li>Locate the unique Assistant ID for your AI agent in the "Installation" Section</li>
            <li>Copy this Assistant ID to your clipboard.</li>
        </ol>

        <h3>4. Paste the Assistant ID into WordPress</h3>
        <p>
            Now, you'll need to integrate your AI agent into your WordPress website:
        </p>
        <ol>
            <li>Log in to your WordPress admin dashboard.</li>
            <li>Find the "AI Assistant Integration" settings.</li>
            <li>Paste the Assistant ID you copied into the "Assistant ID" field.</li>
            <li>Click "Save" to activate the AI agent.</li>
        </ol>

        <h3>The AI agent will be added to all pages on your website.</h3>

        <p>
            Note: For even more precise responses related to your website and business, you can go to <a href="https://app.neexa.ai/#businesses" target="_blank">app.neexa.ai/#businesses</a> to provide additional information.
        </p>
    </div>
<?php
}