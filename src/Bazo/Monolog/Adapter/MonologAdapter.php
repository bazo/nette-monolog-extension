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
		if ($message instanceof \Exception) {
			$message = $message->getMessage();
			$context = [];
		} elseif (is_string($message)) {
			$context = [];
		} else {
			$context = $message;
			unset($context[0]);
			unset($context[1]);
			if (isset($message[1])) {
				$message = preg_replace('#\s*\r?\n\s*#', ' ', trim($message[1]));
			}
		}

		switch ($priority) {
			case self::DEBUG:
				return $this->monolog->addDebug($message, $context);
			case self::CRITICAL:
				return $this->monolog->addCritical($message, $context);
			case self::ERROR:
				return $this->monolog->addError($message, $context);
			case self::EXCEPTION:
				return $this->monolog->addEmergency($message, $context);
			case self::WARNING:
				return $this->monolog->addWarning($message, $context);
			case 'access':
				return $this->monolog->addNotice($message, $context);
			case 'emergency':
				return $this->monolog->addEmergency($message, $context);
			default:
				return $this->monolog->addInfo($message, $context);
		}
	}


}
