<?php

namespace LeDjassa\AdsBundle\Tests\Util;

use LeDjassa\AdsBundle\Util\Date;
use \Datetime;

class DateTest extends \PHPUnit_Framework_TestCase {

	public function testIsToday()
	{
		$result = Date::isToday(new Datetime("now"));
		$this->assertTrue($result);
	}

	public function testIsYesterday()
	{
		$today = new Datetime("now");
		$result = Date::isYesterday($today->modify("-1 day"));
		$this->assertTrue($result);
	}

	public function testGetMonthFrench()
	{
		$result = Date::getMonthFrench(new DateTime("2000-04-01"));
		$this->assertEquals(Date::$monthFrench[3], $result);
	}
}