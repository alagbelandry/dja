<?php

namespace LeDjassa\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\City;
use LeDjassa\AdsBundle\Model\Quarter;
use LeDjassa\AdsBundle\Model\PictureAd;
use LeDjassa\AdsBundle\Model\AdQuery;
use LeDjassa\AdsBundle\Model\CityQuery;
use LeDjassa\AdsBundle\Model\QuarterQuery;
use LeDjassa\AdsBundle\Model\UserTypeQuery;
use LeDjassa\AdsBundle\Model\AdTypeQuery;
use LeDjassa\AdsBundle\Model\PictureAdQuery;

class LocationAjaxController extends Controller
{
    /**
    * Return city list depending area
    * @return Response ajax response city list
    * @Route("/recupererVilles", name="city_in_area_get")
    *
    */
    public function ajaxGetCityInAreaAction()
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
        // todo _format:json pour specifier le content type
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
    public function ajaxGetQuarterInCityAction()
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

    /**
    * Delete picture of ad
    * @return Response true if success otherwise false
    * @Route("supprimerPhotos", name="picture_delete")
    *
    */
    public function ajaxPictureDeleteAction()
    {
        $request = $this->get('request');
        $pictureId = $request->get('pictureId');

        if ($request->isXmlHttpRequest()) {

            PictureAdQuery::create()
                ->findPk($pictureId)
                ->delete();
        }

        $response = new Response(json_encode($pictureId));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}