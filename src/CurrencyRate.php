<?php

namespace Qireel\Money;

use DateTime;

class CurrencyRate
{
	protected $base;
	
	protected $source;
	
	protected $target;
	
	protected $date;
	
	protected $rate;
	
	public function __construct($base, $target, $source, DateTime $date, $rate)
	{
		$this->base = $base;
		$this->target = $target;
		$this->source = $source;
		$this->date = $date;
		$this->rate = $rate;
	}
	
	public function __get(string $name)
	{
		return $this->$name;
	}
}