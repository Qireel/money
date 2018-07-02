<?php

namespace Qireel\Money;

use Qireel\Money\CurrencyRate;

class MeasureRateCalculator
{
    /**
     * @var array List of \Qireel\Money\CurrencyRate objects
     */    
    protected $rates;
    
    public function __construct($rates = [])
    {
        $this->rates = $rates;
    }
    
    /**
     * For the future extendability
     *
     * @param \Qireel\Money\CurrencyRate $rate
     * @return self
     *
     */
    public function addRate(CurrencyRate $rate)
    {
        $this->rates[] = $rate;
        return $this;
    }
        
    /**
     * Does some MATAN (!) and calculates measure value of rates
     *
     * @return float
     *
     */
    public function getResult()
    {
        $sum = .0;
        foreach ($this->rates as $rate) {
            $sum += $rate->rate;
        }
        
        return $sum / count($this->rates);
    }
}