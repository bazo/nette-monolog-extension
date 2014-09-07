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
use Monolog;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Nette;



/**
 * If you have no custom handlers that will write and/or send your messages somewhere,
 * this one will just write them to the log/ directory, just like the default Tracy logger does.
 *
 * @author Elan Ruusamäe <glen@delfi.ee>
 * @author Filip Procházka <filip@prochazka.su>
 */
class FallbackNetteHandler extends ErrorLogHandler
{

	/**
	 * @var string
	 */
	private $appName;

	/**
	 * @var string
	 */
	private $logDir;

	/**
	 * @var LineFormatter
	 */
	private $defaultFormatter;

	/**
	 * @var LineFormatter
	 */
	private $priorityFormatter;



	public function __construct($appName, $logDir, $expandNewlines = FALSE)
	{
		parent::__construct(self::SAPI, Monolog\Logger::DEBUG, TRUE, $expandNewlines);
		$this->appName = $appName;
		$this->logDir = $logDir;

		$this->defaultFormatter = new LineFormatter('[%datetime%] %message% %context% %extra%');
		$this->priorityFormatter = new LineFormatter('[%datetime%] %level_name%: %message% %context% %extra%');
	}



	public function handle(array $record)
	{
		if ($record['channel'] === $this->appName) {
			$this->setFormatter($this->defaultFormatter);
			$record['filename'] = strtolower($record['level_name']);

		} else {
			$this->setFormatter($this->priorityFormatter);
			$record['filename'] = $record['channel'];
		}

		return parent::handle($record);
	}



	/**
	 * {@inheritdoc}
	 */
	protected function write(array $record)
	{
		if ($this->expandNewlines) {
			$entry = '';
			foreach (preg_split('{[\r\n]+}', (string) $record['message']) as $line) {
				$entry .= trim($this->getFormatter()->format(array('message' => $line) + $record)) . PHP_EOL;
			}

		} else {
			$entry = preg_replace('#\s*\r?\n\s*#', ' ', trim($record['formatted'])) . PHP_EOL;
		}

		$file = $this->logDir . '/' . strtolower($record['filename'] ?: 'info') . '.log';
		if (!@file_put_contents($file, $entry, FILE_APPEND | LOCK_EX)) {
			throw new \RuntimeException("Unable to write to log file '$file'. Is directory writable?");
		}
	}

}
