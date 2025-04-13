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


$isAuthenticated = false;

if (1 || get_option('neexa_ai_access_token') && get_option('neexa_ai_access_token') != '') {

    $isAuthenticated = true;

    $accessToken = get_option('neexa_ai_access_token');

    $crm_configured=false;
    $is_agent_live = true; // Example flag (Set this based on the live agent status)
    $agent_avatar_url = 'https://via.placeholder.com/50'; // Replace with actual agent avatar URL
    $whatsapp_configured = true; // Replace with actual condition
    $email_configured = true; // Replace with actual condition
    $instagram_configured = false; // Replace with actual condition
    $website_configured = false;
    $facebook_configured = false;
}

if (!$isAuthenticated) {
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
        <div class="agent-avatar">
          <img src="https://dummyimage.com/50" alt="Agent Avatar">
        </div>
        <div class="agent-name">Alice is Live</div>
      </div>
      <div class="agent-controls">
        <a href="admin.php?page=neexa-agent-settings" class="button">Switch Live</a>
        <a href="https://app.neexa.co/#/agents/edit/alice" target="_blank" class="button button-secondary">Edit</a>
      </div>
    </div>

    <!-- Metric Cards -->
    <a href="https://app.neexa.co/#/inbox" target="_blank" class="card">
      <h3>Conversations Today</h3>
      <p>128</p>
    </a>

    <a href="https://app.neexa.co/#/autonomous-crm" target="_blank" class="card">
      <h3>In CRM</h3>
      <p>3,542</p>
    </a>

    <a href="https://app.neexa.co/#/businesses" target="_blank" class="card">
      <h3>Campaigns This Month</h3>
      <p>7</p>
    </a>
  </div>

  <!-- Agent Config & Channels -->
  <div class="status-strip">
    <div><span class="status-label">Enabled:</span> <?php echo $whatsapp_configured ? '✔' : '❌'; ?> Data Collection | <?php echo $crm_configured ? '✔' : '❌'; ?> CRM</div>
    <div><span class="status-label">Channels:</span> <?php echo $website_configured ? '✔' : '❌'; ?> Website | <?php echo $whatsapp_configured ? '✔' : '❌'; ?> WhatsApp | <?php echo $email_configured ? '✔' : '❌'; ?> Email | <?php echo $instagram_configured ? '✔' : '❌'; ?> Instagram | <?php echo $facebook_configured ? '✔' : '❌'; ?> Facebook</div>
  </div>

  <!-- Quick Links -->
  <div class="quick-links">
    <h2>🔗 Quick Access</h2>
    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
      <a href="https://app.neexa.co/#/inbox" class="button" target="_blank">Go to Conversations</a>
      <a href="https://app.neexa.co/#/autonomous-crm" class="button button-secondary" target="_blank">Go to CRM</a>
      <a href="https://app.neexa.co/#/businesses" class="button" target="_blank">Training AI Agent</a>
    </div>
  </div>

  <!-- Footer -->
  <p class="dashboard-footer">
    Need help? <a href="https://docs.neexa.co/blog?ref=wordpress-plugin" target="_blank">Visit support</a>.
  </p>
</div>

