<?php
namespace Core\Http;

/**
 * HTTP status codes
 */
class HttpStatus {
	public const OK = 200;
	public const CREATED = 201;
	public const NO_CONTENT = 204;
	public const BAD_REQUEST = 400;
	public const UNAUTHORIZED = 401;
	public const FORBIDDEN = 403;
	public const NOT_FOUND = 404;
	public const METHOD_NOT_ALLOWED = 405;
	public const INTERNAL_SERVER_ERROR = 500;
	public const NOT_IMPLEMENTED = 501;

	/**
	 * Get the reason (as text) for a status code
	 *
	 * @param int $code The HTTP status code
	 * @return string The reason phrase
	 */
	public static function getReasonPhrase(int $code): string
	{
		$phrases = [
			self::OK => 'OK',
			self::CREATED => 'Created',
			self::NO_CONTENT => 'No Content',
			self::BAD_REQUEST => 'Bad Request',
			self::UNAUTHORIZED => 'Unauthorized',
			self::FORBIDDEN => 'Forbidden',
			self::NOT_FOUND => 'Not Found',
			self::METHOD_NOT_ALLOWED => 'Method Not Allowed',
			self::INTERNAL_SERVER_ERROR => 'Internal Server Error',
			self::NOT_IMPLEMENTED => 'Not Implemented',
		];

		return $phrases[$code] ?? 'Unknown Status';
	}
}
