<?php

namespace Bazo\Monolog\DI;


/**
 * @author Martin Bažík <martin@bazo.sk>
 */
class MonologExtension extends \Nette\DI\CompilerExtension
{

	private $defaults = [
		'handlers'	 => [],
		'processors' => [],
		'name'		 => 'App',
		'useLogger'	 => TRUE
	];
	private $useLogger;

	public function loadConfiguration()
	{
		$containerBuilder	 = $this->getContainerBuilder();
		$config				 = $this->getConfig($this->defaults);

		$logger = $containerBuilder->addDefinition($this->prefix('monolog'))
				->setClass(\Monolog\Logger::class, [$config['name'], [], []]);

		foreach ($config['handlers'] as $handlerName => $implementation) {
			$this->compiler->parseServices($containerBuilder, [
				'services' => [
					$this->prefix($handlerName) => $implementation,
				],
			]);

			$logger->addSetup('pushHandler', [$this->prefix('@' . $handlerName)]);
		}

		foreach ($config['processors'] as $processorName => $implementation) {
			$this->compiler->parseServices($containerBuilder, [
				'services' => [
					$this->prefix($processorName) => $implementation,
				],
			]);

			$logger->addSetup('pushProcessor', [$this->prefix('@' . $processorName)]);
		}

		$containerBuilder
				->addDefinition($this->prefix('adapter'))
				->addTag('logger')
				->setClass(\Bazo\Monolog\Adapter\MonologAdapter::class, [$this->prefix('@monolog')])
				->setAutowired($this->useLogger)
		;

		$this->useLogger = $config['useLogger'];
	}


	public function afterCompile(\Nette\PhpGenerator\ClassType $class)
	{

		if ($this->useLogger === TRUE) {
			$initialize = $class->methods['initialize'];
			$initialize->addBody('\Tracy\Debugger::setLogger($this->getService(?));', [$this->prefix('adapter')]);
		}
	}


}
