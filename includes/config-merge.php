<?php

$prod_configs = require_once("./config-prod.php");
$other_configs = @include ("./config.php") ?: [];

return array_merge(
    $prod_configs,
    $other_configs
);
