<?php
/**
 * How It Works page
 *
 * @package    Neexa_Ai
 * @subpackage Neexa_Ai/admin/partials
 */
global $neexa_ai_config;
?>

<div class="wrap">
<div class="neexa-how-it-works">

    <h2>
        <img src="<?php echo plugin_dir_url(__FILE__); ?>../img/neexa-logomark.svg"
             alt="Neexa" style="width:24px;height:24px;vertical-align:middle;margin-right:8px;">
        Using Neexa on Your WordPress Site
    </h2>

    <p style="font-size:14px;color:var(--neexa-text-muted);margin-bottom:28px;">
        Follow these steps to get your AI sales agent live on your website.
    </p>

    <div class="neexa-steps">

        <div class="neexa-step">
            <div class="neexa-step-num">1</div>
            <div class="neexa-step-body">
                <h3>Connect Your Neexa Account</h3>
                <p>From the <a href="<?= esc_url($neexa_ai_config['plugin-home-url']) ?>">Home page</a>, click <strong>Connect to Neexa</strong>. A login window will open — sign in with your Neexa credentials. Once authenticated, your account is securely linked to this site.</p>
                <p class="neexa-tip">Don't have an account? Click <strong>Get Started</strong> on the same page to create one free.</p>
            </div>
        </div>

        <div class="neexa-step">
            <div class="neexa-step-num">2</div>
            <div class="neexa-step-body">
                <h3>Choose an AI Agent to Go Live</h3>
                <p>Go to <a href="<?= esc_url($neexa_ai_config['plugin-configuration-url']) ?>">Configure &rarr; AI Agent</a>. You'll see all the agents in your Neexa workspace. Click <strong>Make Live</strong> on the agent you want to appear on this website.</p>
                <p class="neexa-tip">You can create a new agent directly from the Configure page if you don't have one yet.</p>
            </div>
        </div>

        <div class="neexa-step">
            <div class="neexa-step-num">3</div>
            <div class="neexa-step-body">
                <h3>Adjust Widget Settings</h3>
                <p>Go to <a href="<?= esc_url($neexa_ai_config['plugin-configuration-url'] . '&tab=general-settings') ?>">Configure &rarr; General Settings</a> to control:</p>
                <ul>
                    <li><strong>Chat position</strong> — bottom left, center, or right</li>
                    <li><strong>Appearance</strong> — light or dark mode</li>
                    <li><strong>Default visibility</strong> — open on page load, or icon only</li>
                    <li><strong>Mobile style</strong> — greeting only, or greeting with input box</li>
                    <li><strong>Hide &amp; Seek</strong> — widget peeks from the screen edge until hovered</li>
                </ul>
            </div>
        </div>

        <div class="neexa-step">
            <div class="neexa-step-num">4</div>
            <div class="neexa-step-body">
                <h3>Turn the Widget On</h3>
                <p>In <a href="<?= esc_url($neexa_ai_config['plugin-configuration-url'] . '&tab=general-settings') ?>">General Settings</a>, make sure <strong>Visibility Status</strong> is toggled <strong>on</strong> and save. The chat widget will immediately appear on your site's front end.</p>
                <p class="neexa-tip">You can toggle it off at any time to hide the widget without losing your settings.</p>
            </div>
        </div>

        <div class="neexa-step">
            <div class="neexa-step-num">5</div>
            <div class="neexa-step-body">
                <h3>Monitor from the Dashboard</h3>
                <p>The <a href="<?= esc_url($neexa_ai_config['plugin-home-url']) ?>">Home page</a> shows your agent status, conversations this month, CRM deals, and channel connections at a glance. Use the <strong>Quick Access</strong> links to jump straight into:</p>
                <ul>
                    <li><strong>Conversations</strong> — read and respond to customer chats</li>
                    <li><strong>CRM</strong> — track leads and follow-ups</li>
                    <li><strong>Train AI Agent</strong> — add business info, FAQs, products</li>
                </ul>
            </div>
        </div>

    </div>

    <div class="neexa-hiw-footer">
        <a href="https://docs.neexa.co/blog?ref=wordpress-plugin" target="_blank" class="button button-primary">
            Read the full docs &rarr;
        </a>
        <a href="<?= esc_url($neexa_ai_config['plugin-home-url']) ?>" class="button button-secondary" style="margin-left:10px;">
            Back to Home
        </a>
    </div>

</div>
</div>
