<?php

namespace Qireel\Money;

use Qireel\Money\AbstractProvider;
use Qireel\Money\Providers;
use Qireel\Money\CurrencyRate;
use GuzzleHttp\Client as GuzzleClient;
use DateTime;

class Money
{
	protected $providers = [];
	
	public function __construct()
	{
		$this->providers[] = new Providers\CbrXmlProvider(new GuzzleClient);
		$this->providers[] = new Providers\CbrfProvider(new GuzzleClient);
	}
	
	public function registerProvider(AbstractProvider $provider)
	{
		$this->providers[] = $provider;
		return $this;
	}
	
	public function getMeasureRate($target, $base, DateTime $date)
	{
		$mrc = new MeasureRateCalculator;
		
		foreach ($this->providers as $provider){
			$mrc->addRate($provider->getRate($target, $base, $date));
		}
		
		$measure = $mrc->getResult();
		
		return new CurrencyRate($target, $base, $source, $date, $measure);
	}
}