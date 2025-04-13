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

$options = array_merge($neexa_ai_config['default-settings'], get_option('neexa-ai-options', array()));

$neexaResponseError = null;

if ($hasToken) {

    /** the live one */
    $liveAgent = (null);

    $neexaAPI = new Neexa_Ai_Api_Consumer();

    /* get info about active agent */
    $liveAgentId = $options["neexa_ai_active_agent_id"] ?? null;
    if ($liveAgentId) {

        $response = $neexaAPI->get_ai_agent_info($liveAgentId);

        if (!empty($response['success']) && $response['success']) {

            $liveAgent = array_merge(
                $response['data']['data']['attributes'],
                ['id' => $response['data']['data']['id']]
            );
        }
    }

    /** get the others */
    $otherAgents = [];

    $otherAgentsPagination = [];

    $response = $neexaAPI->get_ai_agents([
        'page[cursor]' => $_GET['cursor'] ?? ""
    ]);

    if (!empty($response['success']) && $response['success']) {

        $otherAgents = $response['data']['data'];

        $otherAgentsPagination = $response['data']['meta'];
    } else {
        $neexaResponseError = $response['error'] ?? null;
    }

    global $wp;
    $url =  add_query_arg($wp->query_vars, home_url($wp->request));
    $otherAgentsPagination['page_prev_link'] =  !empty($otherAgentsPagination['prev_cursor']) ?  $url . "&cursor=" . $otherAgentsPagination['prev_cursor'] : "javascript:void(0)";
    $otherAgentsPagination['page_next_link'] =  !empty($otherAgentsPagination['next_cursor']) ?  $url . "&cursor=" . $otherAgentsPagination['next_cursor'] : "javascript:void(0)";
} else {
    $getStartedExplainer = "configure, connect";
    require_once plugin_dir_path(__FILE__) . 'neexa-ai-get-started.php';
    exit();
}
?>


<!-- TAB HTML -->

<?php if (!empty($neexaResponseError)) : ?>
    <div class="notice notice-error" style="margin: 20px;">
        <p><strong>Neexa Error:</strong> <?php echo esc_html($neexaResponseError); ?></p>
    </div>
    <?php return; ?>
<?php endif; ?>

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
                    <div class="agent-avatar" style="background-image: url(<?= empty($liveAgent['avatar']['path']) ? "https://via.placeholder.com/50" : $neexa_ai_config['api-host'] . 'v1/fs/' . $liveAgent['avatar']['path'] ?>);"></div>
                    <div class="agent-info">
                        <div class="agent-name"><?= wp_trim_words($liveAgent['name'] ?? "", 20) ?></div>
                        <div class="agent-desc">Greeting: <?= esc_attr($liveAgent['first_message'] ?? "") ?></div>
                        <div class="agent-meta">Role: <?= ($neexa_ai_config['ai-agent-roles-full-name'][esc_attr($liveAgent['role'] ?? "", 20)]) ?? "Sales Assistant" ?></div>
                        <div class="agent-meta">Business: <?= esc_attr($liveAgent['business']['name'] ?? "") ?></div>
                    </div>
                    <div class="agent-actions">
                        <a href="<?php $neexa_ai_config["frontend-host"] ?>/#/inbox/<?= $liveAgent['id'] ?>/_?show_edit=true" target="_blank" class="edit-btn">Edit</a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="notice notice-warning" style="margin-bottom: 20px;">
                    <p>No AI Agent is live</p>
                </div>

            <?php } ?>

            <div class="section-title">Other Available Agents</div>


            <?php foreach ($otherAgents as $_otherAgent) { ?>
                <?php $otherAgent = array_merge($_otherAgent['attributes'], ['id' => $_otherAgent['id']]); ?>
                <div class="agent-card">
                    <div class="agent-avatar" style="background-image: url(<?= empty($otherAgent['avatar']['path']) ? "https://via.placeholder.com/50" : $neexa_ai_config['api-host'] . 'v1/fs/' . $otherAgent['avatar']['path'] ?>);"></div>
                    <div class="agent-info">
                        <div class="agent-name"><?= wp_trim_words($otherAgent['name'] ?? "", 20) ?></div>
                        <div class="agent-desc">Greeting Message: <?= esc_attr($otherAgent['first_message'] ?? "") ?></div>
                        <div class="agent-meta">Role: <?= ($neexa_ai_config['ai-agent-roles-full-name'][esc_attr($otherAgent['role'] ?? "", 20)]) ?? "Sales Assistant" ?></div>
                        <div class="agent-meta">Business: <?= esc_attr($otherAgent['business']['name'] ?? "") ?></div>
                    </div>
                    <div class="agent-actions">
                        <form method="post" action="options.php">
                            <?php settings_fields('neexa-ai'); ?>
                            <?php $options = array_merge($neexa_ai_config['default-settings'], get_option('neexa-ai-options', array())) ?>
                            <input type="hidden" name="neexa-ai-options[neexa_ai_active_agent_id]" value="<?= $otherAgent['id'] ?>">
                            <button type="submit" class="make-live-btn">Make Live</button>
                            <a href="<?= $neexa_ai_config["frontend-host"] ?>/#inbox/<?= $otherAgent['id'] ?>/_?show_edit=true" target="_blank" class="edit-btn">Edit</a>
                        </form>
                    </div>
                </div>
            <?php } ?>


            <?php if (count($otherAgents) < 1) { ?>
                <div class="notice notice-warning" style="margin-bottom: 20px;">
                    <p>List is empty</p>
                </div>
            <?php } ?>

            <div class="pagination">
                <a class="button <?= empty($otherAgentsPagination["prev_cursor"]) ? "disabled" : "" ?>" href="<?= $otherAgentsPagination["page_prev_link"] ?>">« Prev</a>
                <a class="button <?= empty($otherAgentsPagination["next_cursor"]) ? "disabled" : "" ?>" href="<?= $otherAgentsPagination["page_next_link"] ?>">Next »</a>
            </div>
        </div>
    </div>

    <div class="plugin-tab-content tab-content" id="tab2-content">
        <!-- FORM WRAPPER -->
        <form method="post" action="options.php" id="neexa-settings-form">
            <?php settings_fields('neexa-ai'); ?>

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

            <!-- LIVE STATUS TOGGLE -->
            <div class="material-setting-group">
                <label class="material-setting-label" for="live_status_toggle">Visibility Status</label>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label class="switch">
                        <input class="track-change" type="checkbox" name="neexa-ai-options[live_status]" id="live_status_toggle" value="1" <?php checked($options['live_status'] ?? '', '1'); ?>>
                        <span class="slider round"></span>
                    </label>
                    <span style="font-size: 14px; color: #555;"><?= $options['live_status'] ? 'Currently Showing' : 'Not Showing' ?></span>
                </div>
            </div>

            <!-- SAVE BUTTON -->
            <button type="submit" class="button button-primary" id="save-settings-btn" disabled>Save Settings</button>
        </form>
    </div>
</div>