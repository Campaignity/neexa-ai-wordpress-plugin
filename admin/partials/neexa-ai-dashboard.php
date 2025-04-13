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


$hasToken = get_option('neexa_ai_access_token') && get_option('neexa_ai_access_token') != '';

if ($hasToken) {

    $liveAgent = (null);

    $neexaAPI = new Neexa_Ai_Api_Consumer();

    /* get info about active agent */
    if ($liveAgentId = get_option("neexa-ai-active-agent-id")) {

        $response = $neexaAPI->get_ai_agent_info($liveAgentId, ['append' => 'featureStatus,deploymentStatus']);

        if (!empty($response['success']) && $response['success']) {
            $liveAgent = [
                ...$response['data']['attributes'],
                'id' => $response['data']['id']
            ];
        }
    }

    $crm_configured = false;
    $is_agent_live = true; // Example flag (Set this based on the live agent status)
    $agent_avatar_url = 'https://via.placeholder.com/50'; // Replace with actual agent avatar URL
    $whatsapp_configured = true; // Replace with actual condition
    $email_configured = true; // Replace with actual condition
    $instagram_configured = false; // Replace with actual condition
    $website_configured = false;
    $facebook_configured = false;
    $outreach_configured = false;
} else {
    $getStartedExplainer = "manage, monitor";

    require_once plugin_dir_path(__FILE__) . 'neexa-ai-get-started.php';

    exit();
}
?>

<div class="neexa-dashboard">
    <h1 class="dashboard-header">👋 Welcome to Neexa</h1>

    <div class="analytics-row">
        <!-- Agent Card -->
        <div class="agent-card">
            <div class="agent-info-h">
                <?php if ($liveAgent) { ?>
                    <div class="agent-avatar">
                        <img src="https://dummyimage.com/50" alt="Agent Avatar">
                    </div>
                <?php } ?>
                <?php if ($liveAgent) { ?>
                    <div class="agent-name"><?= wp_trim_words($liveAgent['name'] ?? "",20) ?> is Live</div>
                <?php } else { ?>
                    <div class="notice notice-warning">No AI Agent is Live</div>
                <?php } ?>
            </div>
            <div class="agent-controls">
                <a href="admin.php?page=neexa-agent-settings" class="button">Switch Agent</a>
                <?php if ($liveAgent) { ?>
                    <a href="https://app.neexa.co/#/agents/edit/alice" target="_blank" class="button button-secondary">Edit</a>
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
            <h3>Impressions</h3>
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
            <div><span class="status-label">Enabled:</span> <?php echo $whatsapp_configured ? '✔' : '❌'; ?> Data Collection | <?php echo $crm_configured ? '✔' : '❌'; ?> CRM | <?php echo $outreach_configured ? '✔' : '❌'; ?> Outreach</div>
            <div><span class="status-label">Channels:</span> <?php echo $website_configured ? '✔' : '❌'; ?> Website | <?php echo $whatsapp_configured ? '✔' : '❌'; ?> WhatsApp | <?php echo $email_configured ? '✔' : '❌'; ?> Email | <?php echo $instagram_configured ? '✔' : '❌'; ?> Instagram | <?php echo $facebook_configured ? '✔' : '❌'; ?> Facebook</div>
        </div>
    <?php } ?>

    <!-- Quick Links -->
    <div class="quick-links">
        <h2>🔗 Quick Access</h2>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="https://app.neexa.co/#/inbox<?php echo $liveAgent ? $liveAgent['id'] : '' ?>" class="button" target="_blank">Go to Conversations</a>
            <a href="https://app.neexa.co/#/autonomous-crm<?php echo $liveAgent ? $liveAgent['id'] : '' ?>" class="button button-secondary" target="_blank">Go to CRM</a>
            <a href="https://app.neexa.co/#/businesses<?php echo $liveAgent ? $liveAgent['business']['id'] : '' ?>" class="button" target="_blank">Train AI Agent</a>
        </div>
    </div>

    <!-- Footer -->
    <p class="dashboard-footer">
        Need help? <a href="https://docs.neexa.co/blog?ref=wordpress-plugin" target="_blank">Visit support</a>.
    </p>
</div>