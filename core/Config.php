<?php

namespace Core;

class Config
{
	protected array $parameters;

	public function __construct(array $parameters = [])
	{
		$this->parameters = $parameters;
	}

	public function load($configPath): void
	{
		if (file_exists($configPath)) {
			$config = require $configPath;
			if (is_array($config)) {
				$this->parameters = array_merge($this->parameters, $config);
			}
		}
	}

	public function get($key, $default = null)
	{
		return $this->parameters[$key] ?? $default;
	}

	// Optionally, add a method to set parameters dynamically
	public function set($key, $value): void
	{
		$this->parameters[$key] = $value;
	}
}
