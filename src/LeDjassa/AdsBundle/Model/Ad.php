<?php

namespace LeDjassa\AdsBundle\Model;

use LeDjassa\AdsBundle\Model\om\BaseAd;
use LeDjassa\AdsBundle\Util\Date;

class Ad extends BaseAd
{
	/**
	* Statut created when user create
	*/
	const STATUT_CREATED = 0;

	/**
	* Statut deleted by user
	*/
	const STATUT_DELETED = 1;

	/**
	* Statut abusive moderated
	*/
	const STATUT_ABUSIVE = 2;

	/**
	* Statut erased for old ads
	*/
	const STATUT_ERASED = 3;

	/**
	* Return ad property list
	* @return array property list
	*/
	function getProperties()
	{
		$city = $this->getCity();
		if ($city instanceof City) {
			$nameCity = $city->getName();
			$nameArea = $city->getArea() instanceof Area ? $city->getArea()->getName() : '';
		}

		$titleCategory = $this->getCategory() instanceof Category ? $this->getCategory()->getTitle() : '';

		$nameAdType = $this->getAdType() instanceof AdType ? $this->getAdType()->getName() : '';

		$titleUserType = $this->getUserType() instanceof UserType ? $this->getUserType()->getTitle() : '';
		
		$nameQuarter = $this->getQuarter() instanceof Quarter ? $this->getQuarter()->getName() : '';
		
		$pictureAds = $this->getPictureAds()->toArray();
	
		$properties = array(
			'id'            		=> $this->id,
			'title'         		=> $this->title,
			'description'  	 		=> $this->description,
			'price'         		=> $this->price,
			'publishedDate'   		=> $this->getCreatedAt(),
			'updatedDate'   		=> $this->getUpdatedAt(),
			'publishMonth'   		=> Date::getMonthFrench($this->getCreatedAt()),
			'isPublishToday'        => Date::isToday($this->getCreatedAt()),
			'isPublishYesterDay'    => Date::isYesterday($this->getCreatedAt()),
			'userName'				=> $this->user_name,
			'userEmail'				=> $this->user_email,
			'userPhone'				=> $this->user_phone,
			'nameCity'      		=> $nameCity,
			'nameArea'      		=> $nameArea,
			'nameQuarter'  			=> $nameQuarter, 
			'titleCategory' 		=> $titleCategory,
			'nameAdType'    		=> $nameAdType,
			'titleUserType' 		=> $titleUserType,
			'pictures'				=> $pictureAds
		);

		return $properties;
	}
}