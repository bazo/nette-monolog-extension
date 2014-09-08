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
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class Logger extends Monolog\Logger
{

	/**
	 * @param string $channel
	 * @return CustomChannel
	 */
	public function channel($channel)
	{
		return new CustomChannel($channel, $this);
	}

}
