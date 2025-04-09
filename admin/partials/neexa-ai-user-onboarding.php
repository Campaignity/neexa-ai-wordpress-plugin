<?php
$config = require plugin_dir_path(dirname(__FILE__)) . './../includes/config.php';

$current_user = wp_get_current_user();

$site_info = [
    'siteName'      => get_bloginfo('name'),
    'siteUrl'       => get_site_url(),
    'siteDesc'      => get_bloginfo('description'),
    'userEmail'     => $current_user->user_email,
    'userName'      => $current_user->display_name,
    'userId'        => $current_user->ID,
];
?>

<div id="neexa-ai-onboarding-iframe-container">
    <iframe src="<?= $config["frontend-url"] ?>/#/get-started/wordpress" frameborder="0" class="full-page-iframe"></iframe>
</div>

<script>
    // let us get some info to the iframe
    const iframe = document.querySelector('#neexa-ai-onboarding-iframe-container .full-page-iframe');

    window.addEventListener("message", function(event) {

        // SECURITY: Only allow messages from the correct origin
        const allowedOrigin = window.neexa_ai_env_vars['frontend-url'];

        if (event.origin !== allowedOrigin) {
            return;
        }

        // Handle the message from the iframe
        if (data.type === "request-what-we-know") {
            iframe.contentWindow.postMessage({
                type: "what-you-need-to-know",
                payload: 
            }, '*');
        }
    }, false);
</script>