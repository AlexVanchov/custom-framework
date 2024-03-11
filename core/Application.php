<?php

namespace Core;

use Core\Http\Request;
use Core\Middleware\MiddlewareInterface;
use Core\Middleware\MiddlewareRunner;

class Application
{

	public static $app;
	public Container $container;
	public Config $config;
	protected MiddlewareRunner $middlewareRunner;

	public function __construct(Config $config)
	{
		self::$app = $this;

		// Load configuration
		$this->config = $config;
		$this->container = new Container();
		$this->middlewareRunner = new MiddlewareRunner();
		$this->registerErrorHandling();
		$this->registerCoreBindings();
	}

	public function run(): void
	{
		// Load routes
		$route_config = Application::$app->config->get('routes');
		$router = $this->container->make('router');
		$router->loadRoutes($route_config);

		// Define the final handler as the router dispatch function.
		$finalHandler = function ($request) use ($router) {
			// Assuming $router->dispatch() is adapted to work with $request if necessary.
			return $router->dispatch();
		};

		// Execute middleware stack with the router dispatch as the final step.
		$response = $this->middlewareRunner->run(null, $finalHandler); // Assuming $request is available or null

		// Echo or handle the response. Adapt this part as needed based on your response handling strategy.
		echo $response;
	}

	protected function registerCoreBindings(): void
	{
		// TODO base Bindds form conf
		$this->container->bind('logger', function ($container) {
			$logger = new \Monolog\Logger('name');
			$logger->pushHandler(new \Monolog\Handler\StreamHandler('../storage/logs/app.log', \Monolog\Logger::WARNING));
			return $logger;
		}, true);

		$this->container->bind('container', $this->container);
		foreach ($this->config->get('bindings') as $abstract => $concrete) {
			$this->container->bind($abstract, $concrete);
		}

		$this->container->bind('router', function ($container) {
			return new Router($container);
		}, true);

		$this->container->bind('request', function () {
			return new Request();
		});
	}

	protected function registerErrorHandling(): void
	{
		set_exception_handler([$this, 'handleException']);
		set_error_handler([$this, 'handleError']);
	}

	public function handleException($exception): void
	{
		// Log exception and return a generic error message to the user
		$this->container->make('logger')->error($exception->getMessage(), ['exception' => $exception]);
		http_response_code(500);

		// TODO if dev env
		if (true) {
			throw $exception;
		}
		echo "An error occurred.";
	}

	public function handleError($errno, $errstr, $errfile, $errline): void
	{
		// Convert all errors to ErrorException instances
		$this->handleException(new \ErrorException($errstr, $errno, 0, $errfile, $errline));
	}

	/**
	 * Add a middleware to the application.
	 *
	 * @param MiddlewareInterface $middleware
	 */
	public function addMiddleware(MiddlewareInterface $middleware): void
	{
		$this->middlewareRunner->addMiddleware($middleware);
	}
}
