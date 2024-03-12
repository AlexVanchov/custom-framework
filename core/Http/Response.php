<?php

namespace Core\Http;

/**
 * The Response class represents an HTTP response.
 */
class Response
{
	protected int $statusCode = 200;
	protected array $headers = [];
	protected string $body;

	/**
	 * Set the HTTP status code and the reason phrase.
	 * @param int $code
	 * @return void
	 */
	public function setStatusCode(int $code): void
	{
		$statusText = HttpStatus::getReasonPhrase($code);
		$this->statusCode = $code;
		$this->addHeader('Status', $code . ' ' . $statusText);
		$this->setBody($statusText);
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @return void
	 */
	public function addHeader(string $name, string $value): void
	{
		$this->headers[$name] = $value;
	}

	/**
	 * @param string $content
	 * @return void
	 */
	public function setBody(string $content): void
	{
		$this->body = $content;
	}

	/**
	 * @return void
	 */
	public function send(): void
	{
		http_response_code($this->statusCode);

		foreach ($this->headers as $name => $value) {
			header("$name: $value");
		}

		echo $this->body;
	}

	/**
	 * @param string $content
	 * @param int $statusCode
	 * @return Response
	 */
	public static function make(string $content = '', int $statusCode = 200): Response
	{
		$response = new self();
		$response->setBody($content);
		$response->setStatusCode($statusCode);
		return $response;
	}
}
