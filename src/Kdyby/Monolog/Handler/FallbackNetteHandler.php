<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\Monolog\Handler;

use Kdyby;
use Monolog\Handler\AbstractProcessingHandler;
use Nette;
use Tracy\Logger;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class FallbackNetteHandler extends AbstractProcessingHandler
{

	/**
	 * @var Logger
	 */
	private $logger;



	public function __construct(Logger $logger)
	{
		parent::__construct();
		$this->logger = $logger;
	}



	/**
	 * Writes the record down to the log of the implementing handler
	 *
	 * @param  array|\DateTime[] $record
	 * @return void
	 */
	protected function write(array $record)
	{
		$priority = $record['channel'] === 'damejidlo' ? strtolower($record['level_name']) : $record['channel'];
		$this->logger->log(array($record['datetime']->format('[Y-m-d H-i-s]'), $record['message']), $priority);
	}

}
