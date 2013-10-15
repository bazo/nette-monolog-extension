<?php

namespace Bazo\Monolog\Adapter;

use Monolog\Logger;

/**
 * Description of MonologAdapter
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
			self::DEBUG => Monolog\Logger::DEBUG,
			self::CRITICAL => Monolog\Logger::CRITICAL,
			self::ERROR => Monolog\Logger::ERROR,
			self::INFO => Monolog\Logger::INFO,
			self::WARNING => Monolog\Logger::WARNING
		);

		$level = isset($levelMap[$priority]) ? $levelMap[$priority] : Monolog\Logger::ERROR;
		return $this->monolog->log($level, $message[1].$message[2]);
	}

	public static function register(Logger $monolog)
	{
		\Nette\Diagnostics\Debugger::$logger = new static($monolog);
	}
}

