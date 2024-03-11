<?php

namespace Core\Security;
// TODO IMPLEMENT ME
class CSRF
{
	/**
	 * Generate a CSRF token and store it in the session.
	 *
	 * @return string The CSRF token.
	 */
	public static function generateToken()
	{
		if (!isset($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}
		return $_SESSION['csrf_token'];
	}

	/**
	 * Validate the CSRF token sent in the request against the token stored in the session.
	 *
	 * @param string $token The CSRF token to validate.
	 * @return bool True if the token is valid, false otherwise.
	 */
	public static function validateToken($token)
	{
		if (isset($_SESSION['csrf_token']) && $token === $_SESSION['csrf_token']) {
			unset($_SESSION['csrf_token']); // Invalidate the token after validation
			return true;
		}
		return false;
	}
}
