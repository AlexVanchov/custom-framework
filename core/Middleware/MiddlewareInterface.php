<?php

namespace Core\Middleware;

interface MiddlewareInterface
{
	/**
	 * Handle the request and response.
	 *
	 * @param mixed $request The request object.
	 * @param callable $next The next middleware or final request handler.
	 * @return mixed
	 */
	public function handle($request, callable $next);
}
