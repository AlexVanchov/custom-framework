<?php

namespace Core\Database;


abstract class BaseModel
{
	protected static $table;

	public static function __callStatic($name, $arguments)
	{
		$queryBuilder = new QueryBuilder(Connection::getInstance(), static::$table);
		if (method_exists($queryBuilder, $name)) {
			return call_user_func_array([$queryBuilder, $name], $arguments);
		}

		throw new \BadMethodCallException("Method {$name} does not exist.");
	}
}
