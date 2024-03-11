<?php

namespace Core;

class Logger
{
	const LOG_FILE = '../storage/logs/app.log';

	public static function write($message, $level = 'INFO')
	{
		$date = date('Y-m-d H:i:s');
		$msg = "[$date] [$level] $message\n";
		file_put_contents(self::LOG_FILE, $msg, FILE_APPEND);
	}
}
