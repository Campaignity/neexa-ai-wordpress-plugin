<div class="neexa-get-started-wrap">
    <div class="neexa-get-started-card">

        <div class="neexa-get-started-logo">
            <img src="<?php echo plugin_dir_url(__FILE__); ?>../img/neexa-logomark.svg" alt="Neexa Logo">
        </div>

        <h1 class="neexa-get-started-title">Neexa | Sales AI Agent for B2C Businesses</h1>
        <p class="neexa-get-started-sub">
            Get started with your AI-powered sales assistant — <?= esc_html($getStartedExplainer) ?>, and grow effortlessly.
        </p>

        <div class="neexa-get-started-actions">
            <a id="oauth-login-btn" href="javascript:void(0)" class="neexa-btn-primary">
                Connect to Neexa
            </a>
            <a href="<?= esc_url(admin_url('admin.php?page=get-started-with-neexa')) ?>" class="neexa-btn-outline">
                Create an Account
            </a>
        </div>

        <p class="neexa-get-started-terms">
            By continuing, you agree to our
            <a href="https://campaignity.com/terms/" target="_blank">Terms</a> and
            <a href="https://campaignity.com/privacy/" target="_blank">Privacy Policy</a>.
        </p>
    </div>
</div>

<div id="oauth-dialog" title="Connecting to Neexa" style="display:none;">
    <div style="display:flex;align-items:center;gap:10px;">
        <div style="background:url('<?php echo admin_url('images/spinner.gif'); ?>') no-repeat center;width:20px;height:20px;"></div>
        <p style="margin:0;">Waiting for authentication...</p>
    </div>
</div>
