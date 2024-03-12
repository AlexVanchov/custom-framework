<?php
namespace Core\Session;

class FlashMessages {
	public function __construct() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if (!isset($_SESSION['flash_messages'])) {
			$_SESSION['flash_messages'] = [];
		}
	}

	/**
	 * Add a flash message to the session.
	 *
	 * @param string $key The key under which the message should be stored.
	 * @param mixed $message The message to store.
	 */
	public function add($key, $message) {
		$_SESSION['flash_messages'][$key] = $message;
	}

	/**
	 * Get a flash message by key and remove it from the session.
	 *
	 * @param string $key The key of the message to retrieve.
	 * @return mixed|null The flash message or null if not found.
	 */
	public function get($key) {
		if (isset($_SESSION['flash_messages'][$key])) {
			$message = $_SESSION['flash_messages'][$key];
			unset($_SESSION['flash_messages'][$key]);
			return $message;
		}

		return null;
	}

	/**
	 * Check if there's a flash message for a given key.
	 *
	 * @param string $key The key to check.
	 * @return bool True if there's a message for the given key, false otherwise.
	 */
	public function has($key) {
		return isset($_SESSION['flash_messages'][$key]);
	}

	/**
	 * Clear all flash messages.
	 */
	public function clear() {
		$_SESSION['flash_messages'] = [];
	}
}
