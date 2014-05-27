<?php

namespace Bazo\Monolog\Adapter;

use Bazo\Monolog\Handler\FallbackNetteHandler;
use Monolog\Logger;
use Nette\Diagnostics\Debugger;



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
		$normalised = $message;
		if (is_array($message)) {
			if (count($message) >= 2) {
				array_shift($message); // first entry is probably time
			}

			$normalised = implode($message);
		}

		$levels = $this->monolog->getLevels();
		$level = isset($levels[$uPriority = strtoupper($priority)]) ? $levels[$uPriority] : Logger::INFO;

		switch ($priority) {
			case 'access':
				return $this->monolog->addInfo($normalised, array('priority' => $priority));

			default:
				return $this->monolog->addRecord($level, $normalised, array('priority' => $priority));
		}
	}



	public static function register(Logger $monolog)
	{
		$adapter = new static($monolog);

		if (method_exists('Nette\Diagnostics\Debugger', 'setLogger')) {
			$monolog->pushHandler(new FallbackNetteHandler(Debugger::getLogger()));
			Debugger::setLogger($adapter);

		} else {
			$monolog->pushHandler(new FallbackNetteHandler(Debugger::$logger));
			Debugger::$logger = $adapter;
		}

		return $adapter;
	}

}

