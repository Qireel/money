<?php

use PHPUnit\Framework\TestCase;
use Qireel\Money\Money;

class MoneyTest extends TestCase
{
	protected $money;
	protected $date;
	
	
	public function setUp()
	{
		$this->date = new DateTime('2018-07-01');
		$this->money = new Money;
	}
	
	public function testMeasureIsReal()
	{
		$this->assertEquals(72.9921, $this->money->getMeasureRate('EUR','RUR',$this->date)->rate);
		$this->assertEquals(62.7565, $this->money->getMeasureRate('USD','RUR',$this->date)->rate);
	}
}