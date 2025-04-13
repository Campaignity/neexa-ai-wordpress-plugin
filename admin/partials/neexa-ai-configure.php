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

$hasToken = get_option('neexa_ai_access_token') && get_option('neexa_ai_access_token') != '';

if ($hasToken) {

    /** the live one */
    $liveAgent = (null);

    $neexaAPI = new Neexa_Ai_Api_Consumer();

    /* get info about active agent */
    if ($liveAgentId = get_option("neexa-ai-active-agent-id")) {

        $response = $neexaAPI->get_ai_agent_info($liveAgentId);

        if (!empty($response['success']) && $response['success']) {
            $liveAgent = [
                ...$response['data']['attributes'],
                'id' => $response['data']['id']
            ];
        }
    }


    /** get the others */
    $otherAgents = [];

    $otherAgentsPagination = [];

    $response = $neexaAPI->get_ai_agents();

    if (!empty($response['success']) && $response['success']) {

        $otherAgents = $response['data']['data'];

        $otherAgentsPagination = $response['data']['links'];
    }

    $is_agent_live = true; // Example flag (Set this based on the live agent status)
    $agent_avatar_url = 'https://via.placeholder.com/50'; // Replace with actual agent avatar URL
    $whatsapp_configured = true; // Replace with actual condition
    $email_configured = true; // Replace with actual condition
    $instagram_configured = false; // Replace with actual condition
} else {
    $getStartedExplainer = "configure, connect";
    require_once plugin_dir_path(__FILE__) . 'neexa-ai-get-started.php';
    exit();
}
?>


<!-- TAB HTML -->
<div class="plugin-tab-wrapper neexa-ai-configuration">
    <div class="plugin-tabs" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div style="display: flex; gap: 10px;">
            <div class="plugin-tab tab active" id="tab1-tab">AI Agent</div>
            <div class="plugin-tab tab" id="tab2-tab">General Settings</div>
        </div>
        <a href="<?= $neexa_ai_config["frontend-host"] ?>/#inbox/_/_?show_create=true" target="_blank" style="
    background-color: #3f51b5;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: background-color 0.3s ease;
  " onmouseover="this.style.backgroundColor='#303f9f'" onmouseout="this.style.backgroundColor='#3f51b5'">
            ➕ Create New AI Agent
        </a>
    </div>

    <div class="plugin-tab-content tab-content active" id="tab1-content">
        <div class="agent-section">
            <div class="section-title">Currently Active AI Agent</div>
            <?php if ($liveAgent) { ?>
                <div class="agent-card active">
                    <div class="agent-avatar" style="background-image: url(<?= $liveAgent['avatar']['path'] ?>);"></div>
                    <div class="agent-info">
                        <div class="agent-name"><?= wp_trim_words($liveAgent['name'] ?? "", 20) ?></div>
                        <div class="agent-desc">Greeting: <?= esc_attr($liveAgent['first_message'] ?? "") ?></div>
                        <div class="agent-meta">Role: <?= ['salesman' => "Sales Assistant", "qa-agent" => "Inquiry Assistant"][esc_attr($liveAgent['role'] ?? "", 20)] ?? "Sales Assistant" ?></div>
                        <div class="agent-meta">Business: <?= esc_attr($liveAgent['business']['name'] ?? "") ?></div>
                    </div>
                    <div class="agent-actions">
                        <a href="<?php $neexa_ai_config["frontend-host"] ?>/#/inbox/<?= $liveAgent['id'] ?>?show_edit=true" target="_blank" class="edit-btn">Edit</a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="notice notice-warning" style="margin-bottom: 20px;">
                    <p>No AI Agent is live</p>
                </div>

            <?php } ?>

            <div class="section-title">Other Available Agents</div>

            <div class="agent-card">
                <div class="agent-avatar" style="background-image: url('avatar2.jpg');"></div>
                <div class="agent-info">
                    <div class="agent-name">Neexa SupportBot v1.0</div>
                    <div class="agent-desc">"Let me help you with support!"</div>
                    <div class="agent-meta">Role: Support Specialist</div>
                    <div class="agent-meta">Business: Acme Corp</div>
                </div>
                <div class="agent-actions">
                    <form method="post" action="">
                        <input type="hidden" name="make_live" value="supportbot-1.0">
                        <button type="submit" class="make-live-btn">Make Live</button>
                    </form>
                    <a href="https://edit-link.com/supportbot" target="_blank" class="edit-btn">Edit</a>
                </div>
            </div>

            <div class="agent-card">
                <div class="agent-avatar" style="background-image: url('avatar3.jpg');"></div>
                <div class="agent-info">
                    <div class="agent-name">Neexa FollowUpBot v1.3</div>
                    <div class="agent-desc">"I'll check in again soon!"</div>
                    <div class="agent-meta">Role: Follow-Up Manager</div>
                    <div class="agent-meta">Business: Acme Corp</div>
                </div>
                <div class="agent-actions">
                    <form method="post" action="">
                        <input type="hidden" name="make_live" value="followupbot-1.3">
                        <button type="submit" class="make-live-btn">Make Live</button>
                    </form>
                    <a href="https://edit-link.com/followupbot" target="_blank" class="edit-btn">Edit</a>
                </div>
            </div>

            <div class="pagination">
                <a href="?before=prev_cursor">« Prev</a>
                <a href="?after=next_cursor">Next »</a>
            </div>
        </div>
    </div>

    <div class="plugin-tab-content tab-content" id="tab2-content">
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