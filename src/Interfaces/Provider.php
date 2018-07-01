<?php

namespace Qireel\Money\Interfaces;

use DateTime;

interface Provider
{
	/**
     * Main function of the provider gives a CurrencyRate object.
     *
     * @param string $target
     * @param string $base
     * @param \DateTime $date
     * @return \Qireel\Money\CurrencyRate
     */
	public function getRate($target, $base = 'RUR', DateTime $date = null);
	
	/**
     * Deals with HTTP stuff.
     *
     * @return array $response
	 *
	 * @throws \Qireel\Money\Exceptions\ConnectionException
	 * @throws \Qireel\Money\Exceptions\ResponseException
     */	
	public function query();
}