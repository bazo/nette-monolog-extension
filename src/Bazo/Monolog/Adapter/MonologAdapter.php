<?php

namespace Bazo\Monolog\Adapter;

use Monolog\Logger;

/**
 * MonologAdapter
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class MonologAdapter extends \Nette\Diagnostics\Logger
{
	/** @var Logger */
	private $monolog;

	function __construct(Logger $monolog)
	{
		$this->monolog = $monolog;
	}

	public function log($message, $priority = self::INFO)
	{
		$levelMap = array(
			self::DEBUG => Logger::DEBUG,
			self::CRITICAL => Logger::CRITICAL,
			self::ERROR => Logger::ERROR,
			self::INFO => Logger::INFO,
			self::WARNING => Logger::WARNING
		);

		$level = isset($levelMap[$priority]) ? $levelMap[$priority] : Logger::ERROR;
		return $this->monolog->log($level, $message[1].$message[2]);
	}

	public static function register(Logger $monolog)
	{
		\Nette\Diagnostics\Debugger::$logger = new static($monolog);
	}
}

