<?php

namespace Core\Console;

use Core\Application;
use Core\Database\Connection;

class MigrationCommand
{
	protected $db;
	protected string $migrationsPath = __DIR__ . '/../../migrations';

	public function __construct()
	{
		// TODO FIx me
		$this->db = Application::$app->container->make('database');
		$this->ensureMigrationsTable();
	}

	protected function ensureMigrationsTable()
	{
		$this->db->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
	}

	public function handle()
	{
		$appliedMigrations = $this->getAppliedMigrations();
		$newMigrations = $this->findNewMigrations($appliedMigrations);

		if (empty($newMigrations)) {
			echo "No new migrations to apply.\n";
			return;
		}

		foreach ($newMigrations as $migration) {
			if ($this->applyMigration($migration)) {
				echo "Applied migration: $migration\n";
			} else {
				echo "Failed to apply migration: $migration\n";
			}
		}
	}

	protected function getAppliedMigrations()
	{
		$statement = $this->db->query("SELECT migration FROM migrations");
		return $statement->fetchAll(\PDO::FETCH_COLUMN);
	}

	protected function findNewMigrations($appliedMigrations)
	{
		$files = scandir($this->migrationsPath);
		$migrations = array_diff($files, $appliedMigrations);
		sort($migrations); // Ensure migrations are applied in order
		return $migrations;
	}

	protected function applyMigration($migration)
	{
		$migrationPath = $this->migrationsPath . '/' . $migration;
		require_once $migrationPath;
		$className = pathinfo($migrationPath, PATHINFO_FILENAME);
		$instance = new $className();

		// Transaction
		$this->db->beginTransaction();
		try {
			$this->db->exec($instance->up()); // Apply migration
			$this->db->exec("INSERT INTO migrations (migration) VALUES ('$migration')");
			$this->db->commit();
			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			return false;
		}
	}
}
