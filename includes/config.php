<?php
// Set NEEXA_ENV in your wp-config.php to switch environments:
//   define( 'NEEXA_ENV', 'local' );    → loads config-local.php
//   define( 'NEEXA_ENV', 'staging' );  → loads config-staging.php
// No constant = production (the default for all published installs).
$env  = defined( 'NEEXA_ENV' ) ? NEEXA_ENV : 'prod';
$file = __DIR__ . "/config-{$env}.php";
return file_exists( $file ) ? require $file : require __DIR__ . '/config-prod.php';
