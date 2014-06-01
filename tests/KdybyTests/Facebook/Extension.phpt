<?php

/**
 * Test: Kdyby\Facebook\Extension.
 *
 * @testCase KdybyTests\Facebook\ExtensionTest
 * @author Filip Procházka <filip@prochazka.su>
 * @package Kdyby\Facebook
 */

namespace KdybyTests\Facebook;

use Kdyby;
use KdybyTests;
use Monolog;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class ExtensionTest extends Tester\TestCase
{

	/**
	 * @return \SystemContainer|\Nette\DI\Container
	 */
	protected function createContainer()
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);
		Kdyby\Monolog\DI\MonologExtension::register($config);
		$config->addConfig(__DIR__ . '/../nette-reset.neon', $config::NONE);

		return $config->createContainer();
	}



	public function testFunctional()
	{
		$dic = $this->createContainer();
		Assert::true($dic->getService('monolog.logger') instanceof Monolog\Logger);
	}

}

run(new ExtensionTest());
