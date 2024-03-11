#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Core\Application;
use Core\Config;
use Core\Console\MigrationCommand;

// Load configuration
$config = new Config();
$config->load('../config/config.php');
$app = new Application($config);

$command = $argv[1] ?? null;

switch ($command) {
    case 'migrate':
        $migrationCommand = new MigrationCommand();
        $migrationCommand->handle();
        break;
    // Define other cases for different CLI commands as needed
    default:
        echo "Unknown command.\n";
        break;
}

