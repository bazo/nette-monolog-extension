<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Monolog\Processor;

use Kdyby;
use Monolog\Logger;
use Nette;



/**
 * Helps you change the channel name of the record,
 * when you wanna have multiple log files coming out of your application.
 *
 * @author Filip Procházka <filip@prochazka.su>
 */
class PriorityProcessor extends Nette\Object
{

	public function __invoke($record)
	{
		if (isset($record['context']['channel'])) {
			$record['channel'] = $record['context']['channel'];
			unset($record['context']['channel']);

		} elseif (isset($record['context']['priority'])) {
			$rename = strtoupper($record['context']['priority']);
			if (!in_array($rename, array_keys(Logger::getLevels()), TRUE)) {
				$record['channel'] = strtolower($rename);
			}
			unset($record['context']['priority']);
		}

		return $record;
	}

}
