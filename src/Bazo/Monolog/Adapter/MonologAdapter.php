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


	public function __construct(Logger $monolog)
	{
		$this->monolog = $monolog;
	}


	public function log($message, $priority = self::INFO)
	{

		switch ($priority) {
			case self::DEBUG:
				$res = $this->monolog->addDebug($message[1] . $message[2]);
				break;
			case self::CRITICAL:
				$res = $this->monolog->addCritical($message[1] . $message[2]);
				break;
			case self::ERROR:
				$res = $this->monolog->addError($message[1] . $message[2]);
				break;
			case self::INFO:
				$res = $this->monolog->addInfo($message[1] . $message[2]);
				break;
			case self::WARNING:
				$res = $this->monolog->addWarning($message[1] . $message[2]);
				break;
		}

		return $res;
	}


	public static function register(Logger $monolog)
	{
		\Nette\Diagnostics\Debugger::$logger = new static($monolog);
	}


}

