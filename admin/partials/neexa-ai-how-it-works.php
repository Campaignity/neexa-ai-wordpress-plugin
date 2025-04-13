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
<style>
  .neexa-how-it-works {
    font-family: "Roboto", sans-serif;
    max-width: 800px;
    margin: auto;
    padding: 40px 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    color: #333;
  }

  .neexa-how-it-works h2 {
    font-size: 28px;
    margin-bottom: 30px;
    color: #3f51b5;
  }

  .neexa-how-it-works h3 {
    font-size: 20px;
    margin-top: 25px;
    margin-bottom: 10px;
    color: #222;
  }

  .neexa-how-it-works p {
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 15px;
  }

  .neexa-how-it-works ol {
    padding-left: 20px;
    margin-top: 10px;
    margin-bottom: 20px;
  }

  .neexa-how-it-works ol li {
    margin-bottom: 10px;
  }

  .neexa-how-it-works a {
    color: #2271b1;
    text-decoration: none;
  }

  .neexa-how-it-works a:hover {
    text-decoration: underline;
  }
</style>

<div class="wrap neexa-how-it-works">
  <h2>🚀 How Neexa Works</h2>

  <h3>Step 1: Create or Log Into Your Neexa AI Account</h3>
  <p>
    To use this plugin, you need a Neexa AI account.
    If you already have one, <a href="https://app.neexa.ai/#login" target="_blank">log in here</a>.
    Otherwise, <a href="https://app.neexa.ai/#signup" target="_blank">sign up for free</a>.
  </p>

  <h3>Step 2: Add Business Information</h3>
  <p>
    Help your AI understand your business by adding details like products, services, FAQs, and more. You can do this in two ways:
  </p>
  <ol>
    <li>Type or paste your content manually (from docs, PDFs, etc).</li>
    <li>Use Neexa’s website scraper — just enter your website URL and Neexa will extract the info.</li>
  </ol>

  <h3>Step 3: Create Your AI Assistant</h3>
  <p>
    Go to the <a href="https://app.neexa.ai/#widget-chats" target="_blank">Widgets page</a> and click the ➕ button to create a new assistant. 
    You'll set its name, avatar, role, and link it to your business.
  </p>

  <h3>Step 4: Install on Your Website or WhatsApp</h3>
  <p>
    After creating your assistant, click the ✏️ icon (edit) next to it. 
    In the popup, you’ll find the <strong>Installation</strong> tab with your Assistant ID. 
    Copy this ID and paste it into this plugin's settings.
  </p>
  <p>
    Once saved, the assistant will appear on your website or integrate into WhatsApp — ready to chat with your visitors.
  </p>

  <p>
    Learn more at <a href="https://www.neexa.ai" target="_blank">www.neexa.ai</a>
  </p>
</div>
