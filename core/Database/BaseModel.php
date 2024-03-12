<?php

namespace Core\Database;


use Core\Application;

abstract class BaseModel
{
	protected static $table;

	public static function __callStatic($name, $arguments)
	{
		// TODO FIx me
		$conn = Application::$app->container->make('database');
		$queryBuilder = new QueryBuilder($conn, static::$table);
		if (method_exists($queryBuilder, $name)) {
			return call_user_func_array([$queryBuilder, $name], $arguments);
		}

		throw new \BadMethodCallException("Method {$name} does not exist.");
	}
}
