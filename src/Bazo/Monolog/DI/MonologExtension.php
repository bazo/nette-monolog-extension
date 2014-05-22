<?php

namespace Bazo\Monolog\DI;

/**
 * Monolog extension
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class MonologExtension extends \Nette\DI\CompilerExtension
{

	const TAG_HANDLER = 'monolog.handler';
	const TAG_PROCESSOR = 'monolog.processor';

	private $defaults = [
		'handlers' => [],
		'processors' => [],
		'name' => 'App',
		'useLogger' => TRUE
	];
	
	private $useLogger;


	public function loadConfiguration()
	{
		$containerBuilder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$containerBuilder->addDefinition($this->prefix('logger'))
				->setClass('Monolog\Logger', [$config['name']]);

		foreach ($config['handlers'] as $handlerName => $implementation) {
			$this->compiler->parseServices($containerBuilder, array(
				'services' => array($serviceName = $this->prefix('handler.' . $handlerName) => $implementation),
			));

			$containerBuilder->getDefinition($serviceName)->addTag(self::TAG_HANDLER);
		}

		foreach ($config['processors'] as $processorName => $implementation) {
			$this->compiler->parseServices($containerBuilder, array(
				'services' => array($serviceName = $this->prefix('processor.' . $processorName) => $implementation),
			));

			$containerBuilder->getDefinition($serviceName)->addTag(self::TAG_PROCESSOR);
		}

		$containerBuilder
			->addDefinition($this->prefix('adapter'))
			->addTag('logger')
			->setClass('Bazo\Monolog\Adapter\MonologAdapter', [$this->prefix('@logger')])
		;

		$this->useLogger = $config['useLogger'];
	}



	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$logger = $builder->getDefinition($this->prefix('logger'));

		foreach ($builder->findByTag(self::TAG_HANDLER) as $serviceName => $meta) {
			$logger->addSetup('pushHandler', array('@' . $serviceName));
		}

		foreach ($builder->findByTag(self::TAG_PROCESSOR) as $serviceName => $meta) {
			$logger->addSetup('pushProcessor', array('@' . $serviceName));
		}
	}



	public function afterCompile(\Nette\PhpGenerator\ClassType $class)
	{
		if ($this->useLogger === TRUE) {
			$initialize = $class->methods['initialize'];
			$initialize->addBody('\Nette\Diagnostics\Debugger::$logger = $this->getService(?);', [$this->prefix('adapter')]);
		}
	}


}

