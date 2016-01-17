<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Monolog\Processor;

use Kdyby\Monolog\Diagnostics\TracyLogger;
use Kdyby\Monolog\Diagnostics\TracyLoggerOld;
use Tracy\Debugger;



class TracyExceptionProcessor
{

	/**
	 * @var array
	 */
	private $processedExceptionFileNames = [];

	/**
	 * @var \Kdyby\Monolog\Diagnostics\TracyLogger
	 */
	private $tracyLogger;



	public function __construct($tracyDir)
	{
		if (version_compare(Debugger::VERSION, '2.3.3', '>=')) {
			$this->tracyLogger = new TracyLogger($tracyDir);
		} else {
			$this->tracyLogger = new TracyLoggerOld($tracyDir);
		}
	}



	public function __invoke(array $record)
	{
		if (!isset($record['context']['tracy']) && isset($record['context']['exception']) && $record['context']['exception'] instanceof \Exception) {
			$fileName = $this->tracyLogger->getExceptionFile($record['context']['exception']);

			if (!isset($this->processedExceptionFileNames[$fileName])) {
				$this->tracyLogger->logException($record['context']['exception'], $fileName);
				$this->processedExceptionFileNames[$fileName] = TRUE;
			}

			$record['context']['tracy'] = ltrim(strrchr($fileName, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR);
		}

		return $record;
	}

}
