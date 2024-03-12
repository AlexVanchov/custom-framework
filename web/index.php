<?php
require_once '../config/constants.php';
if (APP_ENV === 'dev') {
	error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_USER_DEPRECATED);
	ini_set('display_startup_errors', '1');
	ini_set('display_errors', '1');
}

require_once '../vendor/autoload.php';
use Core\Application;
use Core\Config;

// Load configuration
$config = new Config();
$config->load('../config/config.php');
$app = new Application($config);

// Register AuthMiddlewares
//$app->addMiddleware(new AuthMiddleware());

// Run the application
$app->run();
