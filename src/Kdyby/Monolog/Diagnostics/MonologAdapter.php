<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\Monolog\Diagnostics;

use Kdyby\Monolog\Handler\FallbackNetteHandler;
use Monolog;
use Tracy\Debugger;
use Tracy\Logger;



/**
 * @author Martin Bažík <martin@bazo.sk>
 * @author Filip Procházka <filip@prochazka.su>
 */
class MonologAdapter extends Logger
{

	/**
	 * @var Monolog\Logger
	 */
	private $monolog;



	public function __construct(Monolog\Logger $monolog)
	{
		$this->monolog = $monolog;
	}



	public function log($message, $priority = self::INFO)
	{
		$context = array();
		$normalised = $message;

		if (is_array($message)) {
			if (count($message) >= 2 && preg_match('~\\[[\\d+ -]+\\]~i', $message[0])) {
				array_shift($message); // first entry is probably time
			}

			if (isset($message[1]) && (preg_match('~\\@\\s+https?:\\/\\/.+~', $message[1])) || preg_match('~CLI:.+~i', $message[1])) {
				$context['at'] = ltrim($message[1], '@ ');
				unset($message[1]);
			}

			if (isset($message[2]) && preg_match('~\\@\\@\\s+exception\\-.+\\.html~', $message[2])) {
				$context['tracy'] = ltrim($message[2], '@ ');
				unset($message[2]);
			}

			$normalised = implode($message);
		}

		$levels = $this->monolog->getLevels();
		$level = isset($levels[$uPriority = strtoupper($priority)]) ? $levels[$uPriority] : Monolog\Logger::INFO;

		switch ($priority) {
			case 'access':
				return $this->monolog->addInfo($normalised, array('priority' => $priority) + $context);

			default:
				return $this->monolog->addRecord($level, $normalised, array('priority' => $priority) + $context);
		}
	}



	public static function register(Monolog\Logger $monolog)
	{
		$adapter = new static($monolog);

		if (method_exists('Tracy\Debugger', 'setLogger')) {
			$monolog->pushHandler(new FallbackNetteHandler(Debugger::getLogger()));
			Debugger::setLogger($adapter);

		} else {
			$monolog->pushHandler(new FallbackNetteHandler(Debugger::$logger));
			Debugger::$logger = $adapter;
		}

		return $adapter;
	}

}
