<?php

namespace Core\Http;

class Request
{
	protected $queryParams = [];
	protected $postParams = [];
	protected $serverParams = [];
	protected $headers = [];

	public function __construct()
	{
		$this->queryParams = $_GET;
		$this->postParams = $_POST;
		$this->serverParams = $_SERVER;
		$this->headers = getallheaders();
	}

	public function query($key, $default = null)
	{
		return $this->queryParams[$key] ?? $default;
	}

	public function post($key, $default = null)
	{
		return $this->postParams[$key] ?? $default;
	}

	public function getMethod()
	{
		return $this->serverParams['REQUEST_METHOD'];
	}

	public function getPathInfo()
	{
		return $this->serverParams['PATH_INFO'] ?? '/';
	}

	public function getHeader($name)
	{
		return $this->headers[$name] ?? null;
	}
}
