<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Monolog\Handler;

use Kdyby;
use Monolog;
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class NewRelicHandler extends Monolog\Handler\NewRelicHandler
{

	/**
	 * {@inheritdoc}
	 */
	protected function write(array $record)
	{
		if (!$this->isNewRelicEnabled()) {
			return;
		}

		parent::write($record);
	}



	/**
	 * {@inheritdoc}
	 */
	public function isHandling(array $record)
	{
		if (!$this->isNewRelicEnabled()) {
			return FALSE;
		}

		return parent::isHandling($record);
	}

}
