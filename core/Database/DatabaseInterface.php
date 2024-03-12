<?php
namespace Core\Database;

interface DatabaseInterface {
	/**
	 * Connect to the database.
	 *
	 * @return mixed
	 */
	public function connect(array $db_config);

	/**
	 * Execute a query and return the result.
	 *
	 * @param string $query
	 * @param array $parameters
	 * @return mixed
	 */
	public function query($query, array $parameters = []);

	/**
	 * Begin a transaction.
	 */
	public function beginTransaction();

	/**
	 * Commit a transaction.
	 */
	public function commit();

	/**
	 * Rollback a transaction.
	 */
	public function rollBack();
}
