<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Monolog\Processor;



class TracyUrlProcessor
{

	/**
	 * @var string
	 */
	private $baseUrl;



	public function __construct($baseUrl)
	{
		$this->baseUrl = rtrim($baseUrl, '/');
	}



	public function __invoke(array $record)
	{
		if (isset($record['context']['tracy'])) {
			$record['context']['tracyUrl'] = sprintf('%s/%s', $this->baseUrl, $record['context']['tracy']);
		}

		return $record;
	}

}
