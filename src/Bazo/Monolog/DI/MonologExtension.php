<?php

namespace Bazo\Monolog\DI;

use Nette\DI\Statement;
use Nette\PhpGenerator\PhpLiteral;



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
		'hookToTracy' => TRUE,
		// 'registerFallback' => TRUE,
	];



	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$builder->addDefinition($this->prefix('logger'))
				->setClass('Monolog\Logger', [$config['name']]);

		foreach ($config['handlers'] as $handlerName => $implementation) {
			$this->compiler->parseServices($builder, array(
				'services' => array($serviceName = $this->prefix('handler.' . $handlerName) => $implementation),
			));

			$builder->getDefinition($serviceName)->addTag(self::TAG_HANDLER);
		}

		foreach ($config['processors'] as $processorName => $implementation) {
			$this->compiler->parseServices($builder, array(
				'services' => array($serviceName = $this->prefix('processor.' . $processorName) => $implementation),
			));

			$builder->getDefinition($serviceName)->addTag(self::TAG_PROCESSOR);
		}

		$builder->addDefinition($this->prefix('adapter'))
			->setClass('Bazo\Monolog\Adapter\MonologAdapter', [$this->prefix('@logger')])
			->addTag('logger');
	}



	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);
		$config = $this->getConfig(array('registerFallback' => empty($config['handlers'])) + $config);

		$logger = $builder->getDefinition($this->prefix('logger'));

		foreach ($builder->findByTag(self::TAG_HANDLER) as $serviceName => $meta) {
			$logger->addSetup('pushHandler', array('@' . $serviceName));
		}

		foreach ($builder->findByTag(self::TAG_PROCESSOR) as $serviceName => $meta) {
			$logger->addSetup('pushProcessor', array('@' . $serviceName));
		}

		if ($config['registerFallback']) {
			$code = method_exists('Nette\Diagnostics\Debugger', 'getLogger')
				? 'Nette\Diagnostics\Debugger::getLogger()'
				: 'Nette\Diagnostics\Debugger::$logger';

			$logger->addSetup('pushHandler', array(
				new Statement('Bazo\Monolog\Handler\FallbackNetteHandler', array(new PhpLiteral($code)))
			));
		}
	}



	public function afterCompile(\Nette\PhpGenerator\ClassType $class)
	{
		$config = $this->getConfig($this->defaults);

		if ($config['hookToTracy'] === TRUE) {
			$initialize = $class->methods['initialize'];

			if (method_exists('Nette\Diagnostics\Debugger', 'setLogger')) {
				$code = '\Nette\Diagnostics\Debugger::setLogger($this->getService(?));';

			} else {
				$code = '\Nette\Diagnostics\Debugger::$logger = $this->getService(?);';
			}

			$initialize->addBody($code, [$this->prefix('adapter')]);
		}
	}

}

