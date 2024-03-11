<?php

namespace Core\Database;

use PDO;

class QueryBuilder
{
	protected $pdo;
	protected $table;
	protected $query;
	protected $bindings = [];

	public function __construct(PDO $pdo, $table)
	{
		$this->pdo = $pdo;
		$this->table = $table;
	}

	public function where($column, $value, $operator = '=')
	{
		$this->query .= " WHERE {$column} {$operator} ?";
		$this->bindings[] = $value;
		return $this;
	}

	public function andWhere($column, $value, $operator = '=')
	{
		// This method adds additional conditions to the WHERE clause.
		$this->query .= " AND {$column} {$operator} ?";
		$this->bindings[] = $value;
		return $this;
	}

	public function get(): bool|array
	{
		$statement = $this->pdo->prepare("SELECT * FROM {$this->table}{$this->query}");
		$statement->execute($this->bindings);
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function first()
	{
		$statement = $this->pdo->prepare("SELECT * FROM {$this->table}{$this->query} LIMIT 1");
		$statement->execute($this->bindings);
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function insert($data): bool|string
	{
		$keys = array_keys($data);
		$fields = implode(', ', $keys);
		$placeholders = ':' . implode(', :', $keys);
		$sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
		$statement = $this->pdo->prepare($sql);

		foreach ($data as $key => $value) {
			$statement->bindValue(":$key", $value);
		}

		$statement->execute();
		return $this->pdo->lastInsertId();
	}

	public function delete(): int
	{
		$sql = "DELETE FROM {$this->table}{$this->query}";
		$statement = $this->pdo->prepare($sql);

		// Execute with bindings to ensure safe deletion.
		$statement->execute($this->bindings);

		return $statement->rowCount();
	}
}