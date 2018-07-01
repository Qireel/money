<?php

namespace Qireel\Money\Interfaces;

use DateTime;

interface Provider
{
	public function getRate($target, $base = 'RUR', DateTime $date = null);
	
	public function query();
}