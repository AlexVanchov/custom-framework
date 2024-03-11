<?php
$config = [
	'bindings' => [
		// TODO implement bindings
//		'logger' => 'Monolog\Logger',
//		'container' => 'Core\Container',
//		'router' => 'Core\Router',
//		'db' => 'Core\Database\Connection',
	],
];

$config['db'] = require 'db.php';
$config['routes'] = require 'routes.php';

return $config;