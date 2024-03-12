<?php

namespace Core\Http;

/**
 * Request class handles the http/s request attributes
 */
class Request
{
	protected array $queryParams = [];
	protected array $postParams = [];
	protected array $serverParams = [];
	protected string|array|false $headers = [];

	public function __construct()
	{
		$this->queryParams = $_GET;
		$this->postParams = $_POST;
		$this->serverParams = $_SERVER;
		$this->headers = getallheaders();
	}

	/**
	 * Get a query parameter.
	 *
	 * @param string $key
	 * @param mixed|null $default
	 * @return mixed
	 */
	public function get(string $key, mixed $default = null): mixed
	{
		return $this->queryParams[$key] ?? $default;
	}

	/**
	 * Get a post parameter.
	 *
	 * @param string $key
	 * @param mixed|null $default
	 * @return mixed
	 */
	public function post(string $key, mixed $default = null): mixed
	{
		return $this->postParams[$key] ?? $default;
	}

	/**
	 * Get the request method.
	 * @return mixed
	 */
	public function getMethod(): mixed
	{
		return $this->serverParams['REQUEST_METHOD'];
	}

	/**
	 * Get the request URI.
	 * @return mixed
	 */
	public function getPathInfo(): mixed
	{
		return $this->serverParams['PATH_INFO'] ?? '/';
	}

	/**
	 * Get the request headers.
	 * @param $name
	 * @return array|string|null
	 */
	public function getHeader($name): array|string|null
	{
		return $this->headers[$name] ?? null;
	}
}
