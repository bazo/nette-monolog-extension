<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\Monolog\DI;

use Nette;
use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Nette\PhpGenerator as Code;



/**
 * @author Martin Bažík <martin@bazo.sk>
 * @author Filip Procházka <filip@prochazka.su>
 */
class MonologExtension extends CompilerExtension
{

	const TAG_HANDLER = 'monolog.handler';
	const TAG_PROCESSOR = 'monolog.processor';

	private $defaults = array(
		'handlers' => array(),
		'processors' => array(),
		'name' => 'App',
		'hookToTracy' => TRUE,
		// 'registerFallback' => TRUE,
	);



	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$builder->addDefinition($this->prefix('logger'))
				->setClass('Monolog\Logger', array($config['name']));

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
			->setClass('Kdyby\Monolog\Diagnostics\MonologAdapter', array($this->prefix('@logger')))
			->addTag('logger');
	}



	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		$logger = $builder->getDefinition($this->prefix('logger'));

		foreach ($handlers = $builder->findByTag(self::TAG_HANDLER) as $serviceName => $meta) {
			$logger->addSetup('pushHandler', array('@' . $serviceName));
		}

		foreach ($builder->findByTag(self::TAG_PROCESSOR) as $serviceName => $meta) {
			$logger->addSetup('pushProcessor', array('@' . $serviceName));
		}

		$config = $this->getConfig(array('registerFallback' => empty($handlers)) + $this->getConfig($this->defaults));

		if ($config['registerFallback']) {
			$code = method_exists('Nette\Diagnostics\Debugger', 'getLogger')
				? 'Nette\Diagnostics\Debugger::getLogger()'
				: 'Nette\Diagnostics\Debugger::$logger';

			$logger->addSetup('pushHandler', array(
				new Statement('Kdyby\Monolog\Handler\FallbackNetteHandler', array(new Code\PhpLiteral($code)))
			));
		}
	}



	public function afterCompile(Code\ClassType $class)
	{
		$config = $this->getConfig($this->defaults);

		if ($config['hookToTracy'] === TRUE) {
			$initialize = $class->methods['initialize'];

			if (method_exists('Nette\Diagnostics\Debugger', 'setLogger')) {
				$code = '\Nette\Diagnostics\Debugger::setLogger($this->getService(?));';

			} else {
				$code = '\Nette\Diagnostics\Debugger::$logger = $this->getService(?);';
			}

			$initialize->addBody($code, array($this->prefix('adapter')));
		}
	}



	public static function register(Configurator $configurator)
	{
		$configurator->onCompile[] = function ($config, Compiler $compiler) {
			$compiler->addExtension('monolog', new MonologExtension());
		};
	}

}

