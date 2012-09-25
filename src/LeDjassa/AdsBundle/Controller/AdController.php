<?php

namespace LeDjassa\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\City;
use LeDjassa\AdsBundle\Model\Quarter;
use LeDjassa\AdsBundle\Model\PictureAd;
use LeDjassa\AdsBundle\Form\Type\AdType;
use LeDjassa\AdsBundle\Form\Type\PictureAdType;
use LeDjassa\AdsBundle\Model\CityQuery;
use LeDjassa\AdsBundle\Model\QuarterQuery;
use LeDjassa\AdsBundle\Model\UserTypeQuery;
use LeDjassa\AdsBundle\Model\AdTypeQuery;

/**
 * @Route("/annonces")
 */
class AdController extends Controller
{
 	/**
	* @Route("/ajouter", name="ad_add")
	* @Template()
	*/
    public function addAction()
    {
    	
        $ad = new Ad();
        
        $form = $this->createForm(new AdType(), $ad);
        
        $request = $this->get('request');
        if ('POST' === $request->getMethod()) { 
        	$form->bindRequest($request);

            if ($form->isValid()) {

                $city = $this->getCity();

                // link ad and city
                $ad->setCity($city);

                // quarter must be link to city
                if ($city instanceof City) {
                    $quarter = $this->getQuarter();
                    if ($quarter instanceof Quarter) {
                        $quarter->setCity($city);
                    }
                }
                if(isset($quarter) && $quarter instanceof Quarter) {
                    $quarter->save();
                }

                $ad->save();
        	}

        	//return $this->redirect($this->generateUrl('ad_success'));
        }
        
        return  array('form' => $form->createView(), 'ad' => $ad);
		
    }

    /**
     * Get post parameter of ad form
     * @return array list parameter or false if list not exist
     */
    function getPostParameters() 
    {
        $request = $this->get('request');
        if ('POST' === $request->getMethod()) {
            return $request->request->get('ad');
        }
            return array();
    }

    /**
     * Get city select in form
     * @return City city select
     */
    function getCity() 
    {
        $parameters = $this->getPostParameters();

        $idCity = isset($parameters['city']['name']) ? $parameters['city']['name'] : '';

        return CityQuery::create()
                ->findOneById($idCity);
    }

    /**
     * Get new quarter depends of name quarter parameter of form
     * @return Quarter|false new quarter or false if it's not
     */
    function getQuarter()
    {
        $parameters = $this->getPostParameters();
        $nameQuarter = isset($parameters['city']['quarter']['name']) ? ucfirst($parameters['city']['quarter']['name']) : '';

        if (empty($nameQuarter)) {
            return false;
        }

        $quarter = QuarterQuery::create()
                      ->findOneByName($nameQuarter);

        // not a new quarter name
        if ($quarter instanceof Quarter) {
            return false;
        }
                
        $quarter = new Quarter();
        $quarter->setName($nameQuarter);

        return $quarter;
    }

    /**
    * Return city list depending area
    * @return Response ajax response city list
    * @Route("/recupererVilles", name="city_in_area_get")
    * 
    */
    public function getCityInAreaAction()
    {
        $cityList = array();
        $request = $this->get('request');
        $areaId = $request->get('areaId');

        if ($request->isXmlHttpRequest()) {
            $cityList = CityQuery::create()
                                ->filterByAreaId($areaId)
                                ->find()
                                ->toKeyValue("Id", "Name"); 
        }
 
        $response = new Response(json_encode($cityList));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
    * Return city list depending area
    * @param Area $area region
    * @return Response ajax response quarter list
    * @Route("/recupererQuartier", name="quarter_in_city_get")
    * 
    */
    public function getQuarterInCityAction()
    {
        $quarterList = array();
        $request = $this->get('request');
        $idCity = $request->get('idCity');

        if ($request->isXmlHttpRequest()) {
            $quarterList = QuarterQuery::create()
                                ->select("Name")
                                ->filterByCityId($idCity)
                                ->find()
                                ->toArray();
        }
    
        $response = new Response(json_encode($quarterList));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}