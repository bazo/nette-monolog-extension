<?php

namespace Bazo\Monolog\Handler;

use Kdyby;
use Monolog\Handler\AbstractProcessingHandler;
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class FallbackNetteHandler extends AbstractProcessingHandler
{

	/**
	 * @var \Nette\Diagnostics\Logger
	 */
	private $logger;



	public function __construct(Nette\Diagnostics\Logger $logger)
	{
		parent::__construct();
		$this->logger = $logger;
	}



	/**
	 * Writes the record down to the log of the implementing handler
	 *
	 * @param  array $record
	 * @return void
	 */
	protected function write(array $record)
	{
		$priority = isset($record['context']['priority']) ? $record['context']['priority'] : strtolower($record['level_name']);
		$this->logger->log(array($record['datetime']->format('[Y-m-d H-i-s]'), $record['message']), $priority);
	}

}
