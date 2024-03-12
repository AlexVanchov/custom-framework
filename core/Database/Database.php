<?php

namespace Core\Database;

use PDO;
use PDOException;

class Database implements DatabaseInterface
{
	protected ?PDO $pdo;

	public function __construct($config)
	{
		$this->connect($config);
	}

	public function connect($db_config): void
	{
		if ($this->pdo === null) {
			try {
				$this->pdo = new PDO(
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
	}


	public function query($sql, $params = [])
	{
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		return $stmt;
	}

	public function beginTransaction()
	{
		return $this->pdo->beginTransaction();
	}

	public function commit()
	{
		return $this->pdo->commit();
	}

	public function rollBack()
	{
		return $this->pdo->rollBack();
	}
}
