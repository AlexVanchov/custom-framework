<?php

namespace Core;

class Router
{
	protected $routes = [];
	protected $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
		$this->loadRoutes();
	}

	public function loadRoutes()
	{
		//TODO  Load routes from the config file
		$routes = require '../config/routes.php';
		foreach ($routes as $route) {
			[$method, $path, $action] = $route;
			$this->add($method, $path, $action);
		}
	}

	public function add($method, $path, $action)
	{
		$this->routes[] = ['method' => $method, 'path' => $path, 'action' => $action];
	}

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

				// TODO or use this
//				call_user_func([$controller, $action]);
				return;
			}
		}

		// Handle not found
		// TODO implement status helper
		header("HTTP/1.0 404 Not Found");
		echo '404 Not Found';
	}
}
