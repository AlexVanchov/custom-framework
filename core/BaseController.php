<?php

namespace Core;

use Core\Http\Request;
use Core\View\View;
use JetBrains\PhpStorm\NoReturn;

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

	protected function model($model)
	{
		$model = "App\\Models\\$model";
		return new $model();
	}

	protected function view($view, $data = []): void
	{
		extract($data);
		require_once "../app/views/$view.php";
	}

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
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

	/**
	 * Set a flash message.
	 *
	 * @param string $key The key for the message.
	 * @param string $message The message content.
	 */
	protected function setFlash(string $key, string $message): void
	{
		// todo move me as separate + bellow
		if (!isset($_SESSION['flash_messages'])) {
			$_SESSION['flash_messages'] = [];
		}
		$_SESSION['flash_messages'][$key] = $message;
	}

	/**
	 * Get and clear a flash message.
	 *
	 * @param string $key The key for the message.
	 * @return string|null The message content or null if not set.
	 */
	protected function getFlash(string $key): ?string
	{
		if (isset($_SESSION['flash_messages'][$key])) {
			$message = $_SESSION['flash_messages'][$key];
			unset($_SESSION['flash_messages'][$key]);
			return $message;
		}
		return null;
	}
}

