<?php

namespace LeDjassa\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\City;
use LeDjassa\AdsBundle\Model\Quarter;
use LeDjassa\AdsBundle\Form\Type\AdType;
use LeDjassa\AdsBundle\Model\CityQuery;
use LeDjassa\AdsBundle\Model\QuarterQuery;

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

            // get parameters list
            $parametersList = $request->request->get('ad');

             // get city
            $idCity = isset($parametersList['city']['name']) ? $parametersList['city']['name'] : '';
            $city = CityQuery::create()
                ->findOneById($idCity);

            // add city to ad
            $ad->setCity($city);

            // add city to quarter
            $nameQuarter = isset($parametersList['city']['quarter']['name']) ? $parametersList['city']['quarter']['name'] : '';
            if ($city instanceof City && !empty($nameQuarter)) {
                $quarter = new Quarter();
                $quarter->setName($nameQuarter)
                        ->setCity($city);
            }

        	if ($form->isValid()) {
        		$ad->save();

                if($quarter instanceof  Quarter) {
                    $quarter->save();
                }
        	}

        	//return $this->redirect($this->generateUrl('ad_success'));
        }
        
        return  array('form' => $form->createView(), 'ad' => $ad);
		
    }

    /**
    * Return city list depending area
    * @param Area $area region
    * @return Response ajax response
    * @Route("/recupererVilles", name="city_from_area_get")
    * 
    */
    public function getCityFromAreaAction()
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
}