<?php

namespace Core\Middleware;

use Core\Http\Request;

class MiddlewareRunner
{
	protected array $middleware = [];

	/**
	 * Add middleware to the stack.
	 *
	 * @param MiddlewareInterface $middleware
	 */
	public function addMiddleware(MiddlewareInterface $middleware): void
	{
		$this->middleware[] = $middleware;
	}

	/**
	 * Run the middleware stack.
	 *
	 * @param Request $request The request object.
	 * @param callable $finalHandler
	 * @return mixed
	 */
	public function run(Request $request, callable $finalHandler): mixed
	{
		$stack = array_reduce(array_reverse($this->middleware), function ($next, $middleware) {
			return function ($request) use ($middleware, $next) {
				return $middleware->handle($request, $next);
			};
		}, $finalHandler);

		return $stack($request);
	}
}
