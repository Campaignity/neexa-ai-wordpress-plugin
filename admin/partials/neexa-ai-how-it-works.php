<?php

/**
 * Provide a admin area view for the plugin
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
?>

<div class="wrap neexa-how-it-works">

  <h2 style="display: flex; flex-wrap:wrap;">
    <div class="neexa-d-logo" style="align-self:center;background-image: url('<?php echo plugin_dir_url(__FILE__); ?>../img/neexa-logomark.svg')"></div>
    <span style="align-self:center">How Neexa Works</span>
  </h2>

  <h3>Step 1: Connect Your Neexa Account</h3>
  <p>
    Get started by <a href="<?= $neexa_ai_config['plugin-home-url'] ?>">logging into your Neexa account</a> or
    <a href="<?= $neexa_ai_config['plugin-home-url'] ?>">signing up for a new one</a>. It's quick and free!
  </p>

  <h3>Step 2: Add Your Business Info</h3>
  <p>
    Let Neexa understand your business. Add important details like your products, services, FAQs, and more.
    You can either:
  </p>
  <ol>
    <li>Manually enter your content (copy-paste from your documents or site), or</li>
    <li>Use Neexa's website scraper to auto-import details from your URL.</li>
  </ol>

  <h3>Step 3: Enable Extra Features</h3>
  <p>
    Unlock powerful tools like CRM, data collection, and outreach automation to supercharge your business.
    These features help streamline customer interactions and maximize engagement.
  </p>

  <h3>Step 4: Connect Your Channels</h3>
  <p>
    Bring your conversations into one place by linking your WhatsApp, Facebook, Instagram, and more.
    Neexa makes it easy to manage all your channels seamlessly.
  </p>

  <h3>Step 5: Add Your WhatsApp Number</h3>
  <p>
    Stay in the loop! Add your WhatsApp number to get instant notifications when there's a new deal
    or a conversation that needs your attention.
  </p>

  <p>
    Learn more at <a href="https://www.neexa.co" target="_blank">www.neexa.co</a>
  </p>
</div>