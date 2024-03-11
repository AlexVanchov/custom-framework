<?php

namespace Core\Database;

use Core\Application;
use PDO;
use PDOException;

class Connection
{
	protected static $instance = null;

	public static function getInstance()
	{
		if (self::$instance === null) {

			$db_config = Application::$app->config->get('db');
			try {
				self::$instance = new PDO(
					$db_config['dsn'],
					$db_config['user'],
					$db_config['password'],
					[
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
					]
				);
			} catch (PDOException $e) {
				// Handle error appropriately
				die("Database Connection Failed: " . $e->getMessage());
			}
		}
		return self::$instance;
	}

	// Prevent creating multiple instances
	private function __construct()
	{
	}

	private function __clone()
	{
	}

	private function __wakeup()
	{
	}
}
