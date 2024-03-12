<?php

namespace Core;

use Core\Http\Request;
use Core\View\View;
use JetBrains\PhpStorm\NoReturn;

/**
 * Base controller
 */
class BaseController
{
	protected View $view;
	protected Request $request;

	public function __construct(Request $request)
	{
		$this->view = new View();
		$this->request = $request;
	}

	/**
	 * @throws \Exception
	 */
	protected function render($template, $variables = []): void
	{
		echo $this->view->render($template, $variables);
	}

	/**
	 * @param $model
	 * @return mixed
	 */
	protected function model($model): mixed
	{
		$model = "App\\Models\\$model";
		return new $model();
	}

	/**
	 * @param $view
	 * @param array $data
	 */
	protected function view($view, $data = []): void
	{
		extract($data);
		require_once "../app/views/$view.php";
	}

	/**
	 * @param $data
	 */
	protected function json($data): void
	{
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	/**
	 * Automatically validate the CSRF token for POST requests.
	 * Call this method at the beginning of any action method that processes a POST request.
	 */
	protected function validateCsrfToken(): void
	{
		if ($this->request->getMethod() === 'POST') {
			$submittedToken = $_POST['csrf_token'] ?? '';
			if (!\Core\Security\CSRF::validateToken($submittedToken)) {
				// Handle the invalid CSRF token case
				// For example, throw an exception or terminate with an error message
				throw new \Exception('CSRF token validation failed.');
			}
		}
	}

	/**
	 * Redirect to a given URL.
	 *
	 * @param string $url The URL to redirect to.
	 */
	#[NoReturn] protected function redirect(string $url): void
	{
		header('Location: ' . $url);
		exit;
	}
}

