<div style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f1f1f1; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">
    <div style="background: #fff; padding: 40px 35px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 100%; max-width: 500px; text-align: center;">

        <!-- Optional Brand Icon -->
        <div style="margin-bottom: 25px;">
            <img src="https://cdn-icons-png.flaticon.com/512/977/977597.png" alt="Neexa Logo" style="width: 50px; height: 50px;" />
        </div>

        <!-- Welcome Heading -->
        <h1 style="font-size: 22px; margin-bottom: 10px; color: #1d2327; font-weight: 600;">Welcome to Neexa Sales Assistant 👋</h1>
        <p style="font-size: 14px; color: #50575e; margin-bottom: 30px;">
            Get started with your AI-powered sales assistant — <?=$getStartedExplainer?>, and grow effortlessly.
        </p>

        <!-- Action Buttons -->
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <a id="oauth-login-btn" href="javascript:void(0)" style="background: #007cba; color: white; padding: 12px; border-radius: 4px; text-decoration: none; font-weight: 500; font-size: 14px;">🔐 Connect to Neexa</a>
            <a href="<?=admin_url('admin.php?page=get-started-with-neexa')?>" style="background: #fff; color: #007cba; border: 1px solid #007cba; padding: 12px; border-radius: 4px; text-decoration: none; font-weight: 500; font-size: 14px;">🚀 Get Started</a>
        </div>

        <!-- Terms Footer -->
        <div style="margin-top: 25px; font-size: 12px; color: #777;">
            By continuing, you agree to our 
            <a href="https://campaignity.com/terms/" style="color: #007cba; text-decoration: none;">Terms</a>.
        </div>
    </div>
</div>

<!-- jQuery Alert Placeholder -->
<div id="oauth-dialog" title="Log Into Neexa" style="display: none;">
  <div style="display: flex; align-items: center; gap: 10px;">
    <div class="oauth-spinner" style="background: url('<?php echo admin_url('images/spinner.gif'); ?>') no-repeat center center;width: 20px; height: 20px; display: inline-block;"></div>
    <p style="margin: 0;">Waiting for authentication...</p>
  </div>
</div>
