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
$is_agent_live = true; // Example flag (Set this based on the live agent status)
$agent_avatar_url = 'https://via.placeholder.com/50'; // Replace with actual agent avatar URL
$whatsapp_configured = true; // Replace with actual condition
$email_configured = true; // Replace with actual condition
$instagram_configured = false; // Replace with actual condition
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="neexa-dashboard" style="font-family: sans-serif; max-width: 1000px; margin: auto; padding: 20px;">
    <h1 style="font-size: 24px; margin-bottom: 20px;">👋 Welcome to Neexa</h1>

    <!-- Analytics Cards -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
        <div style="flex: 2; min-width: 200px; background: #f0f0f1; border-radius: 10px; box-shadow: 0 1px 5px rgba(0,0,0,0.05); padding: 20px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <!-- Agent Live Status -->
                <div style="flex: 1; display: flex; align-items: center;">
                    <!-- Square Avatar -->
                    <div style="width: 50px; height: 50px; margin-right: 15px; overflow: hidden; border:1px solid #dcdcde; border-radius: 5px;">
                        <img src="https://dummyimage.com/50" alt="Agent Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <!-- Agent Live Status -->
                    <div>
                        <h3 style="font-size: 16px; color: #333; margin-bottom: 10px; font-weight: 500;">Alice is Live</h3>
                    </div>
                </div>

                <!-- Toggle Switch and Status Text -->
                <div style="display: flex; align-items: center;">
                    <!-- Toggle Switch -->
                    <label for="toggle-agent" class="switch">
                        <input type="checkbox" id="toggle-agent" <?php echo $is_agent_live ? 'checked' : ''; ?> />
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>


            <div style="display: flex; gap: 5px; margin-top: 20px; flex-wrap: wrap;">

                <div style="display: flex; align-items: center;">
                    <div style="font-size: 14px; font-weight: 500; margin-right: 10px;">
                        <strong>Enabled:</strong>
                    </div>
                </div>

                <!-- WhatsApp Configured -->
                <div style="display: flex; align-items: center;">
                    <div style="font-size: 14px; font-weight: 500; margin-right: 10px;">
                        <?php echo $whatsapp_configured ? '✔' : '❌'; ?>Data collection
                    </div>
                </div>

                <!-- WhatsApp Configured -->
                <div style="display: flex; align-items: center;">
                    <div style="font-size: 14px; font-weight: 500; margin-right: 10px;">
                        <?php echo $whatsapp_configured ? '✔' : '❌'; ?>CRM
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 5px; margin-top: 20px; flex-wrap: wrap;">

                <div style="display: flex; align-items: center;">
                    <div style="font-size: 14px; font-weight: 500; margin-right: 10px;">
                        <strong>Channels:</strong>
                    </div>
                </div>

                <!-- WhatsApp Configured -->
                <div style="display: flex; align-items: center;">
                    <div style="font-size: 14px; font-weight: 500; margin-right: 10px;">
                        <?php echo $whatsapp_configured ? '✔' : '❌'; ?>website
                    </div>
                </div>

                <!-- WhatsApp Configured -->
                <div style="display: flex; align-items: center;">
                    <div style="font-size: 14px; font-weight: 500; margin-right: 10px;">
                        <?php echo $whatsapp_configured ? '✔' : '❌'; ?>WhatsApp
                    </div>
                </div>

                <!-- Email Configured -->
                <div style="display: flex; align-items: center;">
                    <div style="font-size: 14px; font-weight: 500; margin-right: 10px;">
                        <?php echo $email_configured ? '✔' : '❌'; ?>Email
                    </div>
                </div>

                <!-- Instagram Configured -->
                <div class="badge badge-info" style="display: flex; align-items: center;">
                    <div style="font-size: 14px; font-weight: 500; margin-right: 10px;">
                        <?php echo $instagram_configured ? '✔' : '❌'; ?>Instagram
                    </div>
                </div>

                <!-- Instagram Configured -->
                <div style="display: flex; align-items: center;">
                    <div style="font-size: 14px; font-weight: 500; margin-right: 10px;">
                        <?php echo $instagram_configured ? '✔' : '❌'; ?>Facebook
                    </div>
                </div>
            </div>
        </div>

        <a href="https://app.neexa.co/#/inbox" target="_blank" style="text-decoration: none;border-radius: 10px; flex: 1;">
            <div style="min-width: 200px; background: #fff; border-radius: 10px; box-shadow: 0 1px 5px rgba(0,0,0,0.05); padding: 20px;">
                <h3 style="font-size: 16px; margin-bottom: 10px;">Conversations Today</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2271b1;">128</p>
            </div>
        </a>

        <a href="https://app.neexa.co/#/autonomous-crm" target="_blank" style="text-decoration: none;border-radius: 10px; flex: 1;">
            <div style="min-width: 200px; background: #fff; border-radius: 10px; box-shadow: 0 1px 5px rgba(0,0,0,0.05); padding: 20px;">
                <h3 style="font-size: 16px; margin-bottom: 10px;">In CRM</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2271b1;">3,542</p>
            </div>
        </a>

        <a href="https://app.neexa.co/#/businesses" target="_blank" style="text-decoration: none;border-radius: 10px; flex: 1;">
            <div style="min-width: 200px; background: #fff; border-radius: 10px; box-shadow: 0 1px 5px rgba(0,0,0,0.05); padding: 20px;">
                <h3 style="font-size: 16px; margin-bottom: 10px;">Campaigns This Month</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2271b1;">7</p>
            </div>
        </a>
    </div>

    <!-- Quick Links -->
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 18px; margin-bottom: 15px;">🔗 Quick Access</h2>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="https://app.neexa.co/#/inbox" class="button button-primary" target="_blank">Go to Conversations</a>
            <a href="https://app.neexa.co/#/autonomous-crm" class="button button-secondary" target="_blank">Go to CRM</a>
            <a href="https://app.neexa.co/#/businesses" class="button" target="_blank">Business Information</a>
        </div>
    </div>

    <!-- Footer Note -->
    <p style="color: #999; font-size: 12px;">Need help? <a href="https://docs.neexa.co/blog?ref=wordpress-plugin" target="_blank">Visit support</a>.</p>
</div>