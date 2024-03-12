<?php
$config = [
	'bindings' => [
//		'router' => 'Core\Router',
//		'request' => 'Core\Http\Request',
		'db' => 'Core\Database\Connection',
	],
];

$config['db'] = require 'db.php';
$config['routes'] = require 'routes.php';

return $config;