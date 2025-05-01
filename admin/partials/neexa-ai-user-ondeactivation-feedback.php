<?php
$action_url = admin_url('admin-post.php?action=deactivation_feedback');

$skip_url = wp_nonce_url(
    admin_url(
        "plugins.php?action=deactivate&plugin=" . NEEXA_AI_PLUGIN_BASENAME
    ),
    "deactivate-plugin_" . NEEXA_AI_PLUGIN_BASENAME
);
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