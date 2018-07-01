<?php

namespace Qireel\Money;

use Qireel\Money\CurrencyRate;

class MeasureRateCalculator
{
	protected $rates;
	
	public function __construct($rates = [])
	{
		$this->rates = $rates;
	}
	
	public function addRate(CurrencyRate $rate)
	{
		$this->rates[] = $rate;
		return $this;
	}
	
	public function getResult()
	{
		$sum = .0;
		foreach ($this->rates as $rate) {
			$sum += $rate->rate;
		}
		
		return $sum / count($this->rates);
	}
}