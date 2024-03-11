<?php

namespace Core\Http;

class Response
{
	protected $statusCode = 200;
	protected $headers = [];
	protected $body;

	public function setStatusCode($code)
	{
		$this->statusCode = $code;
	}

	public function addHeader($name, $value)
	{
		$this->headers[$name] = $value;
	}

	public function setBody($content)
	{
		$this->body = $content;
	}

	public function send()
	{
		http_response_code($this->statusCode);

		foreach ($this->headers as $name => $value) {
			header("$name: $value");
		}

		echo $this->body;
	}

	// Static factory method for easy instantiation
	public static function make($content = '', $statusCode = 200)
	{
		$response = new self();
		$response->setBody($content);
		$response->setStatusCode($statusCode);
		return $response;
	}
}
