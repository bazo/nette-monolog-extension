<?php

namespace Bazo\Monolog\Adapter;


use Monolog\Logger;

/**
 * @author Martin Bažík <martin@bazo.sk>
 */
class MonologAdapter implements \Tracy\ILogger
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
				return $this->monolog->addDebug($message[1] . $message[2]);
			case self::CRITICAL:
				return $this->monolog->addCritical($message[1] . $message[2]);
			case self::ERROR:
				return $this->monolog->addError($message[1] . $message[2]);
			case self::INFO:
				return $this->monolog->addInfo($message[1] . $message[2]);
			case self::WARNING:
				return $this->monolog->addWarning($message[1] . $message[2]);
			case 'access':
				return $this->monolog->addNotice($message[1] . $message[2]);
			case 'emergency':
				return $this->monolog->addEmergency($message[1] . $message[2]);
		}
	}


}
