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

$activeOptions =  get_option('neexa-ai-active-options');

$hasToken = get_option('neexa_ai_access_token') && get_option('neexa_ai_access_token') != '';

$options = array_merge($neexa_ai_config['default-settings'], get_option('neexa-ai-options', array()));

$neexaResponseError = null;

if ($hasToken) {

    /** the live one */
    $liveAgent = (null);

    $neexaAPI = new Neexa_Ai_Api_Consumer();

    /* get info about active agent */
    $liveAgentId = $activeOptions["id"] ?? null;
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

    $url =  $neexa_ai_config['plugin-configuration-url'];
    $otherAgentsPagination['page_prev_link'] =  !empty($otherAgentsPagination['prev_cursor']) ?  $url . "&cursor=" . $otherAgentsPagination['prev_cursor'] : "javascript:void(0)";
    $otherAgentsPagination['page_next_link'] =  !empty($otherAgentsPagination['next_cursor']) ?  $url . "&cursor=" . $otherAgentsPagination['next_cursor'] : "javascript:void(0)";

    /** active tab */
    $tab = $_GET['tab'] ?? "ai-agent";
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
            <div class="plugin-tab tab <?= $tab == "ai-agent" ? "active" : "" ?>" id="tab1-tab">AI Agent</div>
            <div class="plugin-tab tab <?= $tab == "general-settings" ? "active" : "" ?>" id="tab2-tab">General Settings</div>
            <div class="plugin-tab tab <?= $tab == "authentication" ? "active" : "" ?>" id="tab3-tab">Authentication</div>
        </div>
        <a style="margin-right:10px;" data-href="skip" target="_blank" href="<?= $neexa_ai_config["frontend-host"] ?>/#/inbox/_/_?show_create=true" class="button button-secondary open-in-child">
            + Create New AI Agent
        </a>
    </div>

    <div class="plugin-tab-content tab-content <?= $tab == "ai-agent" ? "active" : "" ?>" id="tab1-content">
        <div class="agent-section">
            <div class="section-title">Currently Active AI Agent</div>
            <?php if ($liveAgent) { ?>
                <div class="agent-card active">
                    <div class="agent-avatar" style="background-image: url(<?= empty($liveAgent['avatar']['path']) ? $neexa_ai_config["frontend-host"] . "/assets/media/neexa-extras/ai-avatar.png" : $neexa_ai_config['api-host'] . 'v1/fs/' . $liveAgent['avatar']['path'] ?>);"></div>
                    <div class="agent-info">
                        <div class="agent-name"><?= wp_trim_words($liveAgent['name'] ?? "", 20) ?></div>
                        <div class="agent-desc">Greeting: <?= esc_attr($liveAgent['first_message'] ?? "") ?></div>
                        <div class="agent-meta">Role: <?= ($neexa_ai_config['ai-agent-roles-full-name'][esc_attr($liveAgent['role'] ?? "", 20)]) ?? "Sales Assistant" ?></div>
                        <div class="agent-meta">Business: <?= esc_attr($liveAgent['business']['name'] ?? "") ?></div>
                    </div>
                    <div class="agent-actions">
                        <div>
                            <a style="margin-right: 5px;" class="button button-secondary" href="<?= $neexa_ai_config['plugin-home-url'] ?>">Home</a>
                            <a data-href="skip" target="_blank" href="<?= $neexa_ai_config["frontend-host"] ?>/#/inbox/<?= $liveAgent['id'] ?>/_?show_edit=true" href="javascript:void(0)" class="button-secondary open-in-child">Edit</a>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="neexa-empty-state" style="align-items:flex-start;padding:12px 0 20px;">
                    <p>No AI Agent is active on this site yet.</p>
                    <p style="font-size:13px;color:var(--neexa-text-muted);margin:0 0 14px;">Select an agent from the list below and click <strong>Make Live</strong>.</p>
                </div>
            <?php } ?>

            <div class="section-title">Other Available Agents</div>


            <?php $hasOthers = false; ?>
            <?php foreach ($otherAgents as $_otherAgent) { ?>
                <?php $otherAgent = array_merge($_otherAgent['attributes'], ['id' => $_otherAgent['id']]); ?>
                <?php if ($otherAgent['id'] == ($liveAgent["id"] ?? null)) continue; ?>
                <div class="agent-card">
                    <div class="agent-avatar" style="background-image: url(<?= empty($otherAgent['avatar']['path']) ? $neexa_ai_config["frontend-host"] . "/assets/media/neexa-extras/ai-avatar.png" : $neexa_ai_config['api-host'] . 'v1/fs/' . $otherAgent['avatar']['path'] ?>);"></div>
                    <div class="agent-info">
                        <div class="agent-name"><?= wp_trim_words($otherAgent['name'] ?? "", 20) ?></div>
                        <div class="agent-desc">Greeting Message: <?= esc_attr($otherAgent['first_message'] ?? "") ?></div>
                        <div class="agent-meta">Role: <?= ($neexa_ai_config['ai-agent-roles-full-name'][esc_attr($otherAgent['role'] ?? "", 20)]) ?? "Sales Assistant" ?></div>
                        <div class="agent-meta">Business: <?= esc_attr($otherAgent['business']['name'] ?? "") ?></div>
                    </div>
                    <div class="agent-actions">
                        <form method="post" action="options.php">
                            <?php settings_fields('neexa-ai-active'); ?>
                            <input type="hidden" name="neexa-ai-active-options[id]" value="<?= $otherAgent['id'] ?>">
                            <button style="margin-right: 5px;" type="submit" class="button button-primary">Make Live</button>
                            <a data-href="skip" target="_blank" href="<?= $neexa_ai_config["frontend-host"] ?>/#/inbox/<?= $otherAgent['id'] ?>/_?show_edit=true" href="javascript:void(0)" class="button button-secondary open-in-child">Edit</a>
                        </form>
                    </div>
                </div>
                <?php $hasOthers = true; ?>
            <?php } ?>

            <?php if (!$hasOthers) { ?>
                <div class="neexa-empty-state" style="align-items:flex-start;padding:12px 0;">
                    <p>No other agents found.</p>
                    <a target="_blank" href="<?= esc_url($neexa_ai_config['frontend-host']) ?>/#/inbox/_/_?show_create=true" class="button button-primary">Create your first AI Agent &rarr;</a>
                </div>
            <?php } ?>

            <div class="pagination">
                <a class="button button-link <?= empty($otherAgentsPagination["prev_cursor"]) ? "disabled" : "" ?>" href="<?= $otherAgentsPagination["page_prev_link"] ?>">« Prev</a>
                <a class="button button-link <?= empty($otherAgentsPagination["next_cursor"]) ? "disabled" : "" ?>" href="<?= $otherAgentsPagination["page_next_link"] ?>">Next »</a>
            </div>
        </div>
    </div>

    <div class="plugin-tab-content tab-content <?= $tab == "general-settings" ? "active" : "" ?>" id="tab2-content">
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

            <!-- MOBILE MINI STYLE -->
            <div class="material-setting-group">
                <label class="material-setting-label">Mobile Mini Style</label>
                <div class="material-radio-group">
                    <?php
                    $mini_styles = [
                        'greeting_only'        => 'Greeting only',
                        'greeting_with_input'  => 'Greeting + input',
                    ];
                    foreach ($mini_styles as $value => $label) :
                    ?>
                        <label class="material-radio">
                            <input class="track-change" type="radio" name="neexa-ai-options[mobile_mini_style]" value="<?php echo esc_attr($value); ?>" <?php checked($options['mobile_mini_style'] ?? '', $value); ?>>
                            <span class="checkmark"></span>
                            <?php echo esc_html($label); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- DEFAULT VISIBILITY -->
            <div class="material-setting-group">
                <label class="material-setting-label">Default Visibility</label>
                <div class="material-radio-group">
                    <?php
                    $visibilities = [
                        'open'   => 'Open',
                        'closed' => 'Closed (icon only)',
                    ];
                    foreach ($visibilities as $value => $label) :
                    ?>
                        <label class="material-radio">
                            <input class="track-change" type="radio" name="neexa-ai-options[default_visibility]" value="<?php echo esc_attr($value); ?>" <?php checked($options['default_visibility'] ?? '', $value); ?>>
                            <span class="checkmark"></span>
                            <?php echo esc_html($label); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- HIDE & SEEK -->
            <div class="material-setting-group">
                <label class="material-setting-label" for="hide_and_seek_toggle">Hide &amp; Seek</label>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label class="switch">
                        <input class="track-change" type="checkbox" name="neexa-ai-options[is_hide_and_seek]" id="hide_and_seek_toggle" value="1" <?php checked($options['is_hide_and_seek'] ?? '', '1'); ?>>
                        <span class="slider round"></span>
                    </label>
                    <span style="font-size: 14px; color: #555;">Widget hides until hovered</span>
                </div>
            </div>

            <!-- HIDE OFFSET -->
            <div class="material-setting-group" id="hide_offset_row" style="<?php echo empty($options['is_hide_and_seek']) ? 'display:none;' : ''; ?>">
                <label class="material-setting-label" for="hide_offset_input">Hide Offset (px)</label>
                <input class="track-change regular-text" type="number" min="10" max="200" id="hide_offset_input"
                    name="neexa-ai-options[hide_offset]"
                    value="<?php echo esc_attr($options['hide_offset'] ?? '20'); ?>">
                <p class="description">Controls how much of the peek tab is visible at the screen edge.</p>
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

    <div class="plugin-tab-content tab-content <?= $tab == "authentication" ? "active" : "" ?>" id="tab3-content">

        <div class="neexa-connection-status">
            <span class="neexa-live-badge" style="background:var(--neexa-success);font-size:11px;">&#10003; Connected</span>
            <span style="font-size:13px;color:var(--neexa-text-muted);margin-left:10px;">
                This WordPress site is linked to your Neexa account.
            </span>
        </div>

        <div class="deauth-box" style="margin-top:24px;">
            <p><strong>Disconnect Neexa</strong></p>
            <p>This will immediately remove the AI agent chat widget from your website and unlink this site from your Neexa account. Your Neexa account and data are not deleted — you can reconnect at any time.</p>
            <form method="post">
                <?php wp_nonce_field('neexa_deauth_action', 'neexa_deauth_nonce'); ?>
                <button type="submit" name="neexa_deauth" class="button button-secondary"
                    onclick="return confirm('Disconnect Neexa from this site? The widget will stop showing immediately.')">
                    Disconnect
                </button>
            </form>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="quick-links" style="margin-top: 30px;">
        <h2>Quick Access</h2>
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <a data-href="skip" target="_blank" href="<?= $neexa_ai_config["frontend-host"] ?>/#/inbox<?php echo $liveAgent ? '/' . $liveAgent['id'] : '' ?>" class="button button-primary open-in-child" href="javascript:void(0)">Go to Conversations</a>
            <a data-href="skip" target="_blank" href="<?= $neexa_ai_config["frontend-host"] ?>/#/autonomous-crm<?php echo $liveAgent ? '/' . $liveAgent['id'] : '' ?>" class="button button-secondary open-in-child" href="javascript:void(0)">Go to CRM</a>
            <a data-href="skip" target="_blank" href="<?= $neexa_ai_config["frontend-host"] ?>/#/businesses<?php echo $liveAgent ? '/' . $liveAgent['business']['id'] : '' ?>" class="button button-primary open-in-child" href="javascript:void(0)">Train AI Agent</a>
        </div>
    </div>

    <!-- Footer -->
    <p class="dashboard-footer">
        Need help? <a href="https://learn.neexa.co?ref=wordpress-plugin" target="_blank">Visit support</a>.
    </p>
</div>