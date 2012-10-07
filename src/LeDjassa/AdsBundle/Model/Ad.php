<?php

namespace LeDjassa\AdsBundle\Model;

use LeDjassa\AdsBundle\Model\om\BaseAd;

class Ad extends BaseAd
{
	/**
	 * Statut created when user create
	 */
	const STATUT_CREATED = 0;

	/**
	 * Statut validated when user valide his password
	 */
	const STATUT_VALIDATED = 1;

	/**
	 * Statut deleted by user
	 */
	const STATUT_DELETED = 2;

	/**
	 * Statut abusive moderated
	 */
	const STATUT_ABUSIVE = 3;

	/**
	 * Statut erased for old ads
	 */
	const STATUT_ERASED = 4;

	/**
	 * Return ad property list
	 * @return array property list
	 */
	function getProperties() 
	{
		$city = $this->getCity();
		if ($city instanceof City) {
			$nameCity = $city->getName();

			$area = $city->getArea();
			if ($area instanceof Area) {
				$nameArea = $area->getName();
			}
		}
		
		$category = $this->getCategory();
		if ($category instanceof Category) {
			$titleCategory = $category->getTitle();	
		}

		$adType = $this->getAdType();
		if ($adType instanceof AdType) {
			$nameAdType = $adType->getName();	
		}

		$userType = $this->getUserType();
		if ($userType instanceof UserType) {
			$titleUserType = $userType->getTitle();
		}

		//$pictureAds = $this->getPictureAds();
		
		$properties = array('id' => $this->id,
							'title' => $this->title,
							'description' => $this->description,
							'price' => $this->price,
							'nameCity' => $nameCity,
							'nameArea' => $nameArea,
							//'nameQuarter' => $this->id, link quarter to ad
							'titleCategory' => $titleCategory,
							'nameAdType' => $nameAdType,
							'titleUserType' => $titleUserType,
							);
	
		return $properties;
	}
}