<?php

namespace Core\Database;

class Database
{
	protected ?\PDO $pdo;

	public function __construct()
	{
		$this->pdo = Connection::getInstance();
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
