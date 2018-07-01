<?php

namespace Qireel\Money;

use Qireel\Money\Interfaces\Provider;
use DateTime;

abstract class AbstractProvider implements Provider
{
	public abstract function getRate($target, $base = 'RUR', DateTime $date = null);
	
	public abstract function query();
}