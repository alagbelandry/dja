<?php

namespace LeDjassa\AdsBundle\Utils;
use \Datetime;

class Date
{
 	static $monthFrench = array(
 		'Janvier',
 		'Février',
 		'Mars',
 		'Avril',
 		'Mai',
 		'Juin',
 		'Juillet',
 		'Aout',
 		'Septembre',
 		'Octobre',
 		'Novembre',
 		'Décembre'
 	);

	/**
	 * Check if date is today
	 * @param datetime $date
	 * @return boolean
	 */
	static function isToday($date) 
	{
		$today = new DateTime("now");
		return $today->format('Y-m-d') === $date->format('Y-m-d');
	}

	/**
	 * Check if date is yesterday
	 * @param datetime $date
	 * @return boolean
	 */
	static function isYesterday($date) 
	{
		$today = new DateTime("now");
		return $today->modify('-1 day')->format('Y-m-d') === $date->format('Y-m-d');
	}

	/**
	 * Return month of date in french
	 * @param datetime $date
	 * @return string month in french
	 */
	static function getMonthFrench($date) 
	{	
		return self::$monthFrench[$date->format('n')];
	}
}
