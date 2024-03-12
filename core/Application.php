<?php

namespace Core;

use Core\Database\Database;
use Core\Http\Request;
use Core\Http\Response;
use Core\Middleware\MiddlewareInterface;
use Core\Middleware\MiddlewareRunner;
use Core\Session\FlashMessages;
use Throwable;

/**
 * Class Application. The main application class.
 * @package Core
 */
class Application
{

	/**
	 * @var Application $app

	 */
	public static Application $app;
	public Container $container;
	public Config $config;
	public Request $request;
	public FlashMessages $flashMessages;
	protected MiddlewareRunner $middlewareRunner;

	/**
	 * Application constructor.
	 * @param Config $config
	 */
	public function __construct(Config $config)
	{
		self::$app = $this;

		// Load configuration
		$this->config = $config;
		$this->container = new Container();
		$this->middlewareRunner = new MiddlewareRunner();
		$this->registerErrorHandling();
		$this->registerCoreBindings();
		$this->registerConfigBindings();

		// TODO
		$this->request = $this->container->get('request');
		$this->flashMessages = $this->container->get('flash');
	}

	/**
	 * Run the application.
	 */
	public function run(): void
	{
		// Load routes
		$route_config = Application::$app->config->get('routes');
		$router = $this->container->get('router');
		$router->loadRoutes($route_config);

		// Define the final handler as the router dispatch function.
		$finalHandler = function ($request) use ($router) {
			// Assuming $router->dispatch() is adapted to work with $request if necessary.
			return $router->dispatch();
		};

		// Execute middleware stack with the router dispatch as the final step.
		$response = $this->middlewareRunner->run(Application::$app->request, $finalHandler); // Assuming $request is available or null

		// Echo or handle the response. Adapt this part as needed based on your response handling strategy.
		echo $response;
	}

	/**
	 * Register core bindings.
	 */
	protected function registerCoreBindings(): void
	{
		$this->container->bind('logger', function ($container) {
			$logger = new \Monolog\Logger('name');
			$logger->pushHandler(new \Monolog\Handler\StreamHandler('../storage/logs/app.log', \Monolog\Logger::WARNING));
			return $logger;
		}, true);


		// TODO fix me
		$this->container->bind('database', function($container) {
			return new Database(Application::$app->config->get('db'));
		});

		$this->container->bind('router', function ($container) {
			return new Router($container);
		}, true);

		$this->container->bind('request', function () {
			return new Request();
		});

		$this->container->bind('flash', function () {
			return new FlashMessages();
		});
	}

	/**
	 * Register bindings from the configuration file.
	 */
	protected function registerConfigBindings(): void
	{
		$bindings = Application::$app->config->get('bindings');

		foreach ($bindings as $abstract => $concrete) {
			$this->container->bind($abstract, function ($container) use ($concrete) {
				return new $concrete($container);
			}, true);
		}
	}

	/**
	 * Register error handling.
	 */
	protected function registerErrorHandling(): void
	{
		set_exception_handler([$this, 'handleException']);
		set_error_handler([$this, 'handleError']);
	}

	/**
	 * Handle an exception.
	 *
	 * @param Throwable $exception
	 * @throws Throwable
	 */
	public function handleException(Throwable $exception): void
	{
		// Log exception and return a generic error message to the user
		$this->container
			->make('logger')
			->error($exception->getMessage(), ['exception' => $exception]);

		if (APP_ENV === 'dev') {
			throw $exception;
		}

		$response = new Response();
		$response->setStatusCode(500);
		$response->send();
	}

	/**
	 * Handle an error.
	 *
	 * @param $errno
	 * @param $errstr
	 * @param $errfile
	 * @param $errline
	 * @throws Throwable
	 */
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
