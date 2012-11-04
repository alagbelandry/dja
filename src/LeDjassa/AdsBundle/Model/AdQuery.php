<?php

namespace LeDjassa\AdsBundle\Model;

use LeDjassa\AdsBundle\Model\om\BaseAdQuery;
use \Criteria;

class AdQuery extends BaseAdQuery
{   
    /**
     * Search ad by category, area and (title or description)
     * @param string $category category search
     * @param string $area area search
     * @param string $title title or description search
     * @return criteria
     */
	public function searchByCategoryAndAreaAndTitleOrDescription($category, $area, $title)
	{
        if ($category instanceof Category) {
            $this->filterByCategory($category);
        }

        if ($area instanceof Area) {
            $cities = $area
                ->getCities()
                ->toKeyValue("Id");

            $this->filterByCityId(array_keys($cities));
        }
     
        if (!empty($title)) {                
            $this
                ->condition('filterByTitle', 'ad.Title LIKE ?', $title) 
                ->condition('filterByDescription', 'ad.Description LIKE ?', $title)
                ->combine(array('filterByTitle', 'filterByDescription'), Criteria::LOGICAL_OR);
        }

        return $this;
	}

    /**
     * Filter by ad live (available)
     */
    public function filterByLive() 
    {
        return $this->filterByStatut(Ad::STATUT_CREATED);
    }

    /**
     * Return list of ad property
     */
    public function getProperties() 
    {
        $adsCollection = $this->find();
        $adProperties = array();
        foreach ($adsCollection as $ad)  {
            $adProperties [$ad->getId()] = $ad->getProperties();
        }

        return $adProperties;
    }
}
