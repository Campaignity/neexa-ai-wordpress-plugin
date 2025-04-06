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

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap neexa-settings">
    <h2>Neexa AI Assistant Configuration</h2>
    <form method="post" action="options.php">
        <?php settings_fields('neexa-ai-agents-config-group'); ?>
        <?php $neexa_ai_agents_configs = get_option('neexa_ai_agents_configs'); ?>


        <table class="form-table">
            <tr valign="top">
                <th scope="row">Assistant ID</th>
                <td><input class="assistant-id" type="text" name="neexa_ai_agents_configs[config_agent_id]" value="<?php echo esc_html(
                                                                                                                        !empty($neexa_ai_agents_configs['config_agent_id'])
                                                                                                                            ? $neexa_ai_agents_configs['config_agent_id']
                                                                                                                            : ""
                                                                                                                    ); ?>" /></td>
            </tr>
        </table>
        <i>The Assistant ID can be got from the <a href="http://app.neexa.ai" target="_blank" rel="noopener noreferrer">neexa.ai dashboard</a></i>

        <p class="submit">
            <input type="submit" class="button-primary" value="Save Changes" />
        </p>
    </form>

    <div>
        Need Help Setting Up an AI Assistant? <a href="https://wa.me/256743665790" target="_blank">
            CONTACT US NOW
        </a> to support you.
    </div>
</div>