<?php

$prod_configs = require_once(__DIR__."/config-prod.php");
$other_configs = @include (__DIR__."/config.php") ?: [];

return array_merge(
    $prod_configs,
    $other_configs
);
