<?php
$config = require plugin_dir_path(dirname(__FILE__)) . './../includes/config.php';
?>

<div id="neexa-ai-onboarding-iframe-container">
    <iframe src="<?= $config["frontend-url"] ?>/#/get-started/wordpress" frameborder="0" class="full-page-iframe"></iframe>
</div>
