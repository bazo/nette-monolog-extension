<?php

/**
 * Test: Kdyby\Monolog\FallbackNetteHandler.
 *
 * @testCase KdybyTests\Monolog\FallbackNetteHandlerTest
 * @author Filip Procházka <filip@prochazka.su>
 * @package Kdyby\Monolog
 */

namespace KdybyTests\Monolog;

use Kdyby;
use Kdyby\Monolog\Handler\FallbackNetteHandler;
use Monolog;
use Monolog\Formatter\NormalizerFormatter;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class FallbackNetteHandlerTest extends Tester\TestCase
{

	/**
	 * @var FallbackNetteHandler
	 */
	private $handler;

	/**
	 * @var \DateTime
	 */
	private $now;

	/**
	 * @var string
	 */
	private $logDir;



	protected function setUp()
	{
		$this->logDir = TEMP_DIR . '/log_' . getmypid() . '_' . number_format(microtime(TRUE), 6, '+', '');
		@mkdir($this->logDir, 0777, TRUE);

		foreach (array_merge(glob($this->logDir . '/*.log'), glob($this->logDir . '/*.html')) as $logFile) {
			unlink($logFile);
		}

		$this->handler = new FallbackNetteHandler('kdyby', $this->logDir);

		$this->now = new \DateTime();
	}



	public function dataWrite_standardLevels()
	{
		return array(
			array(Monolog\Logger::DEBUG, 'debug'),
			array(Monolog\Logger::INFO, 'info'),
			array(Monolog\Logger::NOTICE, 'notice'),
			array(Monolog\Logger::WARNING, 'warning'),
			array(Monolog\Logger::ERROR, 'error'),
			array(Monolog\Logger::CRITICAL, 'critical'),
			array(Monolog\Logger::ALERT, 'alert'),
			array(Monolog\Logger::EMERGENCY, 'emergency'),
		);
	}



	/**
	 * @dataProvider dataWrite_standardLevels
	 */
	public function testWrite_standardLevels($level, $levelName)
	{
		$this->handler->handle(array(
			'message' => "test message",
			'context' => array(),
			'level' => $level,
			'level_name' => strtoupper($levelName),
			'channel' => 'kdyby',
			'datetime' => $this->now,
			'extra' => array(),
		));

		Assert::match(
			'[%a%] test message [] []',
			file_get_contents($this->logDir . '/' . $levelName . '.log')
		);
	}



	public function testWrite_customChannel()
	{
		$this->handler->handle(array(
			'message' => "test message",
			'context' => array(),
			'level' => Monolog\Logger::INFO,
			'level_name' => 'INFO',
			'channel' => 'nemam',
			'datetime' => $this->now,
			'extra' => array(),
		));

		$this->handler->handle(array(
			'message' => "test message",
			'context' => array(),
			'level' => Monolog\Logger::WARNING,
			'level_name' => 'WARNING',
			'channel' => 'nemam',
			'datetime' => $this->now,
			'extra' => array(),
		));

		Assert::match(
			'[%a%] INFO: test message [] []' . "\n" .
			'[%a%] WARNING: test message [] []',
			file_get_contents($this->logDir . '/nemam.log')
		);
	}



	public function testWrite_contextAsJson()
	{
		$this->handler->handle(array(
			'message' => "test message",
			'context' => array('at' => 'http://www.kdyby.org', 'tracy' => 'exception-2014-08-14-11-11-26-88167e58be9dc0dfd12a61b3d8d33838.html'),
			'level' => Monolog\Logger::INFO,
			'level_name' => 'INFO',
			'channel' => 'custom',
			'datetime' => $this->now,
			'extra' => array(),
		));

		Assert::match(
			'[%a%] INFO: test message {"at":"http://www.kdyby.org","tracy":"exception-2014-08-14-11-11-26-88167e58be9dc0dfd12a61b3d8d33838.html"} []',
			file_get_contents($this->logDir . '/custom.log')
		);
	}



	public function testWrite_extraAsJson()
	{
		$this->handler->handle(array(
			'message' => "test message",
			'context' => array(),
			'level' => Monolog\Logger::INFO,
			'level_name' => 'INFO',
			'channel' => 'custom',
			'datetime' => $this->now,
			'extra' => array('secret' => 'no animals were harmed during writing this test case'),
		));

		Assert::match(
			'[%a%] INFO: test message [] {"secret":"no animals were harmed during writing this test case"}',
			file_get_contents($this->logDir . '/custom.log')
		);
	}

}

\run(new FallbackNetteHandlerTest());
