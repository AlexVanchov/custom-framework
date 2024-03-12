<?php

namespace Core;

/**
 * The Config class is a simple key-value store for configuration parameters.
 */
class Config
{
	protected array $parameters;

	public function __construct(array $parameters = [])
	{
		$this->parameters = $parameters;
	}

	/**
	 * @param $configPath
	 * @return void
	 */
	public function load($configPath): void
	{
		if (file_exists($configPath)) {
			$config = require $configPath;
			if (is_array($config)) {
				$this->parameters = array_merge($this->parameters, $config);
			}
		}
	}

	/**
	 * @param $key
	 * @param $default
	 * @return mixed|null
	 */
	public function get($key, $default = null)
	{
		return $this->parameters[$key] ?? $default;
	}

	/**
	 * @param $key
	 * @param $value
	 * @return void
	 */
	public function set($key, $value): void
	{
		$this->parameters[$key] = $value;
	}
}
