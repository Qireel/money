<?php

namespace Qireel\Money;

use Qireel\Money\Interfaces\Provider;
use DateTime;

abstract class AbstractProvider implements Provider
{
    /**
     * Main function of the provider gives a CurrencyRate object.
     *
     * @param string $target
     * @param string $base
     * @param \DateTime $date
     * @return \Qireel\Money\CurrencyRate
     */
    public abstract function getRate($target, $base = 'RUR', DateTime $date = null);
    
    /**
     * Deals with HTTP stuff.
     *
     * @return array $response
     *
     * @throws \Qireel\Money\Exceptions\ConnectionException
     * @throws \Qireel\Money\Exceptions\ResponseException
     */
    public abstract function query();
}