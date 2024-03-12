<?php

namespace Core;

use Core\Http\HttpStatus;
use Core\Http\Response;

/**
 * The Router class is responsible for routing requests to the appropriate controller action.
 */
class Router
{
	protected array $routes = [];
	protected Container $container;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
		$this->loadRoutes();
	}

	/**
	 * @return void
	 */
	public function loadRoutes(): void
	{
		$routes = Application::$app->config->get('routes');
		foreach ($routes as $route) {
			[$method, $path, $action] = $route;
			$this->add($method, $path, $action);
		}
	}

	/**
	 * @param $method
	 * @param $path
	 * @param $action
	 * @return void
	 */
	public function add($method, $path, $action)
	{
		$this->routes[] = ['method' => $method, 'path' => $path, 'action' => $action];
	}

	/**
	 * @return void
	 */
	public function dispatch(): void
	{
		$url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
		$method = $_SERVER['REQUEST_METHOD'];

		foreach ($this->routes as $route) {
			if ($route['path'] === $url && $route['method'] === $method) {
				list($class, $action) = explode('@', $route['action']);
				$class = "App\\Controllers\\$class";
				$controller = $this->container->make($class);
				$controller->$action();
				return;
			}
		}

		$this->handleNotFound();
	}

	/**
	 * @return void
	 */
	protected function handleNotFound(): void
	{
		$response = new Response();
		$response->setStatusCode(HttpStatus::NOT_FOUND);
		$response->send();
	}
}
