<?php

namespace Core\Middleware;

use Core\Middleware\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
	/**
	 * Handle an incoming request.
	 *
	 * @param mixed $request The request object.
	 * @param callable $next The next middleware or final handler.
	 * @return mixed
	 */
	public function handle($request, callable $next)
	{
		// Check if the user is authenticated
		if (!$this->isAuthenticated()) {
			// Redirect to login page or return a response indicating unauthorized access
			header('Location: /login');
			exit;
		}

		// User is authenticated, proceed to the next middleware or the final request handler
		return $next($request);
	}

	/**
	 * A placeholder for your authentication logic.
	 * Implement this method based on your application's auth system.
	 *
	 * @return bool True if the user is authenticated, false otherwise.
	 */
	protected function isAuthenticated()
	{
		// TODO Implement authentication check and remove me from here
		return isset($_SESSION['user']);
	}
}
