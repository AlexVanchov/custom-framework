<?php

namespace Core\View;
class View
{
	protected $path;
	protected $variables = [];

	public function __construct($path = "../app/Views")
	{
		$this->path = $path;
	}

	public function assign($key, $value)
	{
		$this->variables[$key] = $value;
	}

	public function render($template, $variables = [])
	{
		$fullPath = $this->path . '/' . $template . '.php';
		if (!file_exists($fullPath)) {
			throw new \Exception("Template $template not found in $fullPath.");
		}

		$variables = array_merge($this->variables, $variables);

		extract($variables);

		ob_start();
		include $fullPath;
		$content = ob_get_clean();

		return $content;
	}

	/**
	 * Escape HTML special characters
	 *
	 * @param string $string
	 * @return string
	 */
	public static function escape(string $string): string
	{
		return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	}
}
