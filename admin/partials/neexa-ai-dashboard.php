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

    $stats = [];

    $liveAgent = (null);

    $neexaAPI = new Neexa_Ai_Api_Consumer();

    /* get info about active agent */
    $liveAgentId = $activeOptions["id"] ?? null;

    if ($liveAgentId) {

        /* agent info */
        $response = $neexaAPI->get_ai_agent_info($liveAgentId, ['append' => 'featureStatus,deploymentStatus']);
        
        if (!empty($response['success']) && $response['success']) {
            $liveAgent = array_merge(
                $response['data']['data']['attributes'],
                ['id' => $response['data']['data']['id']]
            );
        }

        /* agent statistics */
        $response = $neexaAPI->get_ai_agent_summary_stats($liveAgentId);
        if (!empty($response['success']) && $response['success']) {
            $stats = $response['data'];
        } else {
            $neexaResponseError = $response['error'] ?? null;
        }
    }
} else {
    $getStartedExplainer = "manage, monitor";

    require_once plugin_dir_path(__FILE__) . 'neexa-ai-get-started.php';

    exit();
}
?>

<?php if (!empty($neexaResponseError)) : ?>
    <div class="notice notice-error" style="margin: 20px;">
        <p><strong>Neexa Error:</strong> <?php echo esc_html($neexaResponseError); ?></p>
    </div>
    <?php return; ?>
<?php endif; ?>

<div class="neexa-dashboard">
    <h1 class="dashboard-header">
        <div class="neexa-d-logo" style="background-image: url('<?php echo plugin_dir_url(__FILE__); ?>../img/neexa-logomark.svg')"></div>
        <span>Welcome to Neexa</span>
    </h1>

    <div class="analytics-row">
        <!-- Agent Card -->
        <div class="agent-card">
            <div class="agent-info-h">
                <?php if ($liveAgent) { ?>
                    <div class="agent-avatar">
                        <img src="<?= empty($liveAgent['avatar']['path']) ? $neexa_ai_config["frontend-host"] . "/assets/media/neexa-extras/ai-avatar.png" : $neexa_ai_config['api-host'] . 'v1/fs/' . $liveAgent['avatar']['path'] ?>" alt="Agent Avatar">
                    </div>
                <?php } ?>
                <?php if ($liveAgent) { ?>
                    <div class="agent-name">
                        <?= wp_trim_words($liveAgent['name'] ?? "", 20) ?>
                        <span class="neexa-live-badge">Live</span>
                    </div>
                <?php } else { ?>
                    <div class="neexa-empty-state">
                        <p>No AI Agent is configured yet.</p>
                        <a href="<?= esc_url($neexa_ai_config['plugin-configuration-url']) ?>" class="button button-primary">Configure an Agent &rarr;</a>
                    </div>
                <?php } ?>
            </div>
            <div class="agent-controls">
                <a href="<?= $neexa_ai_config['plugin-configuration-url'] ?>" class="button">Switch Agent</a>
                <?php if ($liveAgent) { ?>
                    <a data-href="skip" target="_blank" href="<?= $neexa_ai_config["frontend-host"] ?>/#/inbox/<?= $liveAgent['id'] ?>/_?show_edit=true" href="javascript:void(0)" class="button button-secondary open-in-child">Edit</a>
                <?php } ?>
            </div>
        </div>

        <!-- Conversations -->
        <div class="card">
            <h3>Conversations</h3>
            <p><?php echo $stats['conversations']['conversations_this_month'] ?? 0; ?></p>
            <span class="card-subtext">This Month</span>
        </div>

        <!-- Impressions -->
        <div class="card">
            <h3>Reach</h3>
            <p><?php echo $stats['conversations']['checkouts_this_month'] ?? 0; ?></p>
            <span class="card-subtext">This Month</span>
        </div>

        <!-- CRM Deals Won -->
        <div class="card">
            <h3>CRM - Deals Won</h3>
            <p><?php echo $stats['crm']['won'] ?? 0; ?></p>
            <span class="card-subtext">This Month</span>
        </div>

        <!-- Currently Following -->
        <div class="card">
            <h3>Currently Following</h3>
            <p><?php echo $stats['crm']['currently_following'] ?? 0; ?></p>
        </div>

        <!-- Won Value per Currency -->
        <?php if (!empty($stats['crm']['won_value_per_currency'])): ?>
            <?php foreach ($stats['crm']['won_value_per_currency'] as $item): ?>
                <div class="card">
                    <h3>Won Value (<?php echo $item['currency']; ?>)</h3>
                    <p><?php echo number_format($item['value'], 2); ?></p>
                    <span class="card-subtext">This Month</span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Agent Config & Channels -->
    <?php if ($liveAgent) { ?>
        <div class="status-strip">
            <div>
                <span class="status-label">Enabled:</span>
                <span class="status-icon <?php echo $liveAgent['feature_status']['data_collection'] ? 'material-check' : 'material-cross'; ?>">
                    <?php echo $liveAgent['feature_status']['data_collection'] ? '✔ Data Collection' : '✖ Data Collection'; ?>
                    <a data-href="skip" target="_blank" href="<?= $neexa_ai_config['frontend-host'] ?>/#/inbox/<?= $liveAgent["id"] ?>/_?show_edit=true&tab=automations&subtab=data-collection" href="javascript:void(0)" class="open-in-child">Edit</a>
                </span>
                <span class="status-icon <?php echo $liveAgent['feature_status']['crm'] ? 'material-check' : 'material-cross'; ?>">
                    <?php echo $liveAgent['feature_status']['crm'] ? '✔ CRM' : '✖ CRM'; ?>
                    <a data-href="skip" target="_blank" href="<?= $neexa_ai_config['frontend-host'] ?>/#/inbox/<?= $liveAgent["id"] ?>/_?show_edit=true&tab=automations&subtab=follow-ups" href="javascript:void(0)" class="open-in-child">Edit</a>
                </span>
                <span class="status-icon <?php echo $liveAgent['feature_status']['out_reach'] ? 'material-check' : 'material-cross'; ?>">
                    <?php echo $liveAgent['feature_status']['out_reach'] ? '✔ Outreach' : '✖ Outreach'; ?>
                    <a data-href="skip" target="_blank" href="<?= $neexa_ai_config['frontend-host'] ?>/#/out-reach" href="javascript:void(0)" class="open-in-child">Edit</a>
                </span>
            </div>
            <div>
                <span class="status-label">Channels:</span>
                <span class="status-icon <?php echo isset($options['live_status']) &&  $options['live_status'] ? 'material-check' : 'material-cross'; ?>">
                    <?php echo isset($options['live_status']) &&  $options['live_status'] ? '✔ Website' : '✖ Website'; ?>
                    <a href="<?= $neexa_ai_config['plugin-configuration-url'] ?>&tab=general-settings">Edit</a>
                </span>
                <span class="status-icon <?php echo $liveAgent['deployment_status']['whatsapp'] ? 'material-check' : 'material-cross'; ?>">
                    <?php echo $liveAgent['deployment_status']['whatsapp'] ? '✔ WhatsApp' : '✖ WhatsApp'; ?>
                    <a data-href="skip" target="_blank" href="<?= $neexa_ai_config['frontend-host'] ?>/#/inbox/<?= $liveAgent["id"] ?>/_?show_edit=true&tab=deploy&subtab=whatsapp" href="javascript:void(0)" class="open-in-child">Edit</a>
                </span>
                <span class="status-icon <?php echo $liveAgent['deployment_status']['email'] ? 'material-check' : 'material-cross'; ?>">
                    <?php echo $liveAgent['deployment_status']['email'] ? '✔ Email' : '✖ Email'; ?>
                    <a data-href="skip" target="_blank" href="<?= $neexa_ai_config['frontend-host'] ?>/#/inbox/<?= $liveAgent["id"] ?>/_?show_edit=true&tab=deploy&subtab=email" href="javascript:void(0)" class="open-in-child">Edit</a>
                </span>
                <span class="status-icon <?php echo $liveAgent['deployment_status']['instagram'] ? 'material-check' : 'material-cross'; ?>">
                    <?php echo $liveAgent['deployment_status']['instagram'] ? '✔ Instagram' : '✖ Instagram'; ?>
                    <a data-href="skip" target="_blank" href="<?= $neexa_ai_config['frontend-host'] ?>/#/inbox/<?= $liveAgent["id"] ?>/_?show_edit=true&tab=deploy&subtab=instagram" href="javascript:void(0)" class="open-in-child">Edit</a>
                </span>
                <span class="status-icon <?php echo $liveAgent['deployment_status']['facebook'] ? 'material-check' : 'material-cross'; ?>">
                    <?php echo $liveAgent['deployment_status']['facebook'] ? '✔ Facebook' : '✖ Facebook'; ?>
                    <a data-href="skip" target="_blank" href="<?= $neexa_ai_config['frontend-host'] ?>/#/inbox/<?= $liveAgent["id"] ?>/_?show_edit=true&tab=deploy&subtab=facebook" href="javascript:void(0)" class="open-in-child">Edit</a>
                </span>
            </div>
        </div>

    <?php } ?>

    <!-- Quick Links -->
    <div class="quick-links">
        <h2>🔗 Quick Access</h2>
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <a data-href="skip" target="_blank" href="<?= $neexa_ai_config["frontend-host"] ?>/#/inbox<?php echo $liveAgent ? '/' . $liveAgent['id'] : '' ?>" class="button button-primary open-in-child" href="javascript:void(0)">Go to Conversations</a>
            <a data-href="skip" target="_blank" href="<?= $neexa_ai_config["frontend-host"] ?>/#/autonomous-crm<?php echo $liveAgent ? '/' . $liveAgent['id'] : '' ?>" class="button button-secondary open-in-child" href="javascript:void(0)">Go to CRM</a>
            <a data-href="skip" target="_blank" href="<?= $neexa_ai_config["frontend-host"] ?>/#/businesses<?php echo $liveAgent ? '/' . $liveAgent['business']['id'] : '' ?>" class="button button-primary open-in-child" href="javascript:void(0)">Train AI Agent</a>
        </div>
    </div>

    <!-- Footer -->
    <p class="dashboard-footer">
        Need help? <a href="https://docs.neexa.co/blog?ref=wordpress-plugin" target="_blank">Visit support</a>.
    </p>
</div>