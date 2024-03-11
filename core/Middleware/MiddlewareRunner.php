<?php

namespace Core\Middleware;

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
	 * TODO here
	 * Run the middleware stack.
	 *
	 * @param mixed $request The request object.
	 * @param callable $finalHandler
	 * @return mixed
	 */
	public function run(mixed $request, callable $finalHandler): mixed
	{
		$stack = array_reduce(array_reverse($this->middleware), function ($next, $middleware) {
			return function ($request) use ($middleware, $next) {
				return $middleware->handle($request, $next);
			};
		}, $finalHandler);

		return $stack($request);
	}
}
