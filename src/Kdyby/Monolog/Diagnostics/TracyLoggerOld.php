<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Monolog\Diagnostics;

use Tracy\Logger;



class TracyLoggerOld extends Logger
{

	/**
	 * @return string
	 */
	public function getExceptionFile(\Exception $exception)
	{
		return parent::getExceptionFile($exception);
	}



	/**
	 * @return string
	 */
	public function logException(\Exception $exception, $file = NULL)
	{
		return parent::logException($exception, $file);
	}

}
