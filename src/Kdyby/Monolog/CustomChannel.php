<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Monolog;

use Kdyby;
use Monolog;
use Monolog\Handler\HandlerInterface;
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class CustomChannel extends Monolog\Logger
{

	/**
	 * @var Logger
	 */
	private $parentLogger;



	public function __construct($name, Logger $parentLogger)
	{
		parent::__construct($name, array(), array());
		$this->parentLogger = $parentLogger;
	}



	/**
	 * {@inheritdoc}
	 */
	public function pushHandler(HandlerInterface $handler)
	{
		$this->parentLogger->pushHandler($handler);
	}



	/**
	 * {@inheritdoc}
	 * @return HandlerInterface
	 */
	public function popHandler()
	{
		return $this->parentLogger->popHandler();
	}



	/**
	 * {@inheritdoc}
	 * @return HandlerInterface[]
	 */
	public function getHandlers()
	{
		return $this->parentLogger->getHandlers();
	}



	/**
	 * {@inheritdoc}
	 */
	public function pushProcessor($callback)
	{
		$this->parentLogger->pushProcessor($callback);
	}



	/**
	 * {@inheritdoc}
	 * @return callable
	 */
	public function popProcessor()
	{
		return $this->parentLogger->popProcessor();
	}



	/**
	 * {@inheritdoc}
	 * @return callable[]
	 */
	public function getProcessors()
	{
		return $this->parentLogger->getProcessors();
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function addRecord($level, $message, array $context = array())
	{
		return $this->parentLogger->addRecord($level, $message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function addDebug($message, array $context = array())
	{
		return $this->parentLogger->addDebug($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function addInfo($message, array $context = array())
	{
		return $this->parentLogger->addInfo($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function addNotice($message, array $context = array())
	{
		return $this->parentLogger->addNotice($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function addWarning($message, array $context = array())
	{
		return $this->parentLogger->addWarning($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function addError($message, array $context = array())
	{
		return $this->parentLogger->addError($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function addCritical($message, array $context = array())
	{
		return $this->parentLogger->addCritical($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function addAlert($message, array $context = array())
	{
		return $this->parentLogger->addAlert($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function addEmergency($message, array $context = array())
	{
		return $this->parentLogger->addEmergency($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function isHandling($level)
	{
		return $this->parentLogger->isHandling($level);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function log($level, $message, array $context = array())
	{
		return $this->parentLogger->log($level, $message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function debug($message, array $context = array())
	{
		return $this->parentLogger->debug($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function info($message, array $context = array())
	{
		return $this->parentLogger->info($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function notice($message, array $context = array())
	{
		return $this->parentLogger->notice($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function warn($message, array $context = array())
	{
		return $this->parentLogger->warn($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function warning($message, array $context = array())
	{
		return $this->parentLogger->warning($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function err($message, array $context = array())
	{
		return $this->parentLogger->err($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function error($message, array $context = array())
	{
		return $this->parentLogger->error($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function crit($message, array $context = array())
	{
		return $this->parentLogger->crit($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function critical($message, array $context = array())
	{
		return $this->parentLogger->critical($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function alert($message, array $context = array())
	{
		return $this->parentLogger->alert($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function emerg($message, array $context = array())
	{
		return $this->parentLogger->emerg($message, array('channel' => $this->name) + $context);
	}



	/**
	 * {@inheritdoc}
	 * @return Boolean Whether the record has been processed
	 */
	public function emergency($message, array $context = array())
	{
		return $this->parentLogger->emergency($message, array('channel' => $this->name) + $context);
	}

}
