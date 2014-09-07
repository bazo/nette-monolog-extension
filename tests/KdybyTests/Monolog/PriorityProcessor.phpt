<?php

/**
 * Test: Kdyby\Monolog\PriorityProcessor.
 *
 * @testCase KdybyTests\Monolog\PriorityProcessorTest
 * @author Filip Procházka <filip@prochazka.su>
 * @package Kdyby\Monolog
 */

namespace KdybyTests\Monolog;

use Kdyby;
use Kdyby\Monolog\Processor\PriorityProcessor;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class PriorityProcessorTest extends Tester\TestCase
{

	public function dataFunctional()
	{
		return array(
			array(
				array('channel' => 'kdyby', 'context' => array()),
				array('channel' => 'kdyby', 'context' => array('priority' => 'debug')),
			),
			array(
				array('channel' => 'kdyby', 'context' => array()),
				array('channel' => 'kdyby', 'context' => array('priority' => 'info')),
			),
			array(
				array('channel' => 'kdyby', 'context' => array()),
				array('channel' => 'kdyby', 'context' => array('priority' => 'notice')),
			),
			array(
				array('channel' => 'kdyby', 'context' => array()),
				array('channel' => 'kdyby', 'context' => array('priority' => 'warning')),
			),
			array(
				array('channel' => 'kdyby', 'context' => array()),
				array('channel' => 'kdyby', 'context' => array('priority' => 'error')),
			),
			array(
				array('channel' => 'kdyby', 'context' => array()),
				array('channel' => 'kdyby', 'context' => array('priority' => 'critical')),
			),
			array(
				array('channel' => 'kdyby', 'context' => array()),
				array('channel' => 'kdyby', 'context' => array('priority' => 'alert')),
			),
			array(
				array('channel' => 'kdyby', 'context' => array()),
				array('channel' => 'kdyby', 'context' => array('priority' => 'emergency')),
			),

			// when bluescreen is rendered Tracy
			array(
				array('channel' => 'exception', 'context' => array()),
				array('channel' => 'kdyby', 'context' => array('priority' => 'exception')),
			),

			// custom priority
			array(
				array('channel' => 'nemam', 'context' => array()),
				array('channel' => 'kdyby', 'context' => array('priority' => 'nemam')),
			),

			// custom channel provided in $context parameter when adding record
			array(
				array('channel' => 'emails', 'context' => array()),
				array('channel' => 'kdyby', 'context' => array('channel' => 'emails')),
			),
			array(
				array('channel' => 'smses', 'context' => array()),
				array('channel' => 'kdyby', 'context' => array('channel' => 'smses')),
			),
		);
	}



	/**
	 * @dataProvider dataFunctional
	 */
	public function testFunctional($expectedRecord, $providedRecord)
	{
		Assert::same($expectedRecord, call_user_func(new PriorityProcessor(), $providedRecord));
	}

}

\run(new PriorityProcessorTest());
