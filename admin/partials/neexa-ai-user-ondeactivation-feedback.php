<?php
$action_url = admin_url('admin.php?page=neexa-feedback-before-deactivate');

$skip_url = wp_nonce_url(
    admin_url(
        "plugins.php?action=deactivate&plugin=" . NEEXA_AI_PLUGIN_BASENAME
    ),
    "deactivate-plugin_" . NEEXA_AI_PLUGIN_BASENAME
);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('neexa_feedback_nonce')) {
    $reason = stripslashes(sanitize_text_field($_POST['neexa_reason'] ?? ''));
    $extra_feedback = $reason === 'other' ? stripslashes(sanitize_textarea_field($_POST['neexa_feedback'] ?? '')) : '';

    $feedback_data = [
        'status' => 'pending',
        'reason'        => $reason,
        'plugin_name'   => 'Neexa AI',
        'site_url'      => site_url(),
        'message'       => $extra_feedback,
        'plugin_version' => NEEXA_AI_VERSION,
        'submitted_at'  => current_time('mysql'),
    ];

    $api_consumer = new Neexa_Ai_Api_Consumer();

    $api_consumer->send_feedback_to_platform($feedback_data);
    //!todo: handle send failures

    deactivate_plugins(NEEXA_AI_PLUGIN_BASENAME);

    wp_redirect(admin_url('plugins.php?deactivated=true'));

    exit;
}
?>

<div id="neexa-feedback-modal">
    <div class="neexa-modal-content">
        <h2>We'd love your feedback!</h2>
        <form method="post" action="<?php echo esc_url($action_url); ?>">
            <p>We're sorry to see you go! Could you tell us why you are deactivating Neexa AI?</p>

            <label class="neexa-feedback-option"><input type="radio" name="neexa_reason"
                    value="It's not what I expected" required> It's not what I expected</label><br>
            <label class="neexa-feedback-option"><input type="radio" name="neexa_reason"
                    value="I found a better plugin" required> I found a better plugin</label><br>
            <label class="neexa-feedback-option"><input type="radio" name="neexa_reason"
                    value="Temporary deactivation" required> Temporary deactivation</label><br>
            <label class="neexa-feedback-option"><input type="radio" name="neexa_reason"
                    value="Plugin caused errors" required> Plugin caused errors</label><br>
            <label class="neexa-feedback-option"><input type="radio" name="neexa_reason"
                    value="Missing features I need" required> Missing features I need</label><br>
            <label class="neexa-feedback-option"><input type="radio" name="neexa_reason"
                    value="It is complicated. I don't understand it" required> It is complicated. I don't understand it</label><br>
            <label class="neexa-feedback-option"><input type="radio" name="neexa_reason"
                    value="other" required> Other (please specify)</label><br>

            <div id="neexa-other-textarea" style="display:none; margin-top:10px;">
                <textarea name="neexa_feedback" rows="4" placeholder="Tell us more..."></textarea>
            </div>

            <?php wp_nonce_field('neexa_feedback_nonce'); ?>

            <div class="neexa-buttons">
                <input type="submit" class="button button-primary" value="Submit & Deactivate">
                <a href="<?php echo esc_url($skip_url); ?>" class="button">Skip & Deactivate</a>
            </div>
        </form>
    </div>
</div>