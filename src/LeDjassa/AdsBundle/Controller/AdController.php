<?php

namespace LeDjassa\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\PictureAd;
use FOS\UserBundle\Model\User;
use LeDjassa\AdsBundle\Model\City;
use LeDjassa\AdsBundle\Model\Quarter;
use LeDjassa\AdsBundle\Form\Type\AdType;
use LeDjassa\AdsBundle\Form\Type\PictureAdType;
use LeDjassa\UserBundle\Form\Type\RegistrationFormType;
use LeDjassa\AdsBundle\Model\AdQuery;
use LeDjassa\AdsBundle\Model\CityQuery;
use LeDjassa\AdsBundle\Model\QuarterQuery;
use LeDjassa\AdsBundle\Model\UserTypeQuery;
use LeDjassa\AdsBundle\Model\AdTypeQuery;
use LeDjassa\AdsBundle\Form\Handler\AdHandler;


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

        $formHandler = new AdHandler($form, $request);
        $process = $formHandler->process();

        if ($process) {

            return $this->redirect($this->generateUrl('ad_validate'));

        } elseif ('GET' === $request->getMethod()) {

            return  array('form' => $form->createView(), 'ad' => $ad);

        } else {
            throw new Exception("Unknow Request Method in Form Ad");  
        }
    }

    /**
    * @Route("/valider", name="ad_validate")
    * @Template()
    */
    public function validateAction()
    {   
            // get datas ad
            $request = $this->get('request');
            $session = $request->getSession();

            if (!$session->has('idAd')) {
                throw $this->createNotFoundException("The ad idendifier is empty in validate ad process");  
            }

            $ad = AdQuery::create()
                    ->findOneById($session->get('idAd'));

            if (!$ad instanceof Ad) {
                throw $this->createNotFoundException('The ad does not exist');    
            }
            $adProperties = $ad->getProperties();

            // get datas user
            $dataUser = array();
            if (!$session->has('username')) {
                throw $this->createNotFoundException("User name does exist in session");  
            }
            $dataUser['username'] = $session->get('username');

            if (!$session->has('email')) {
                throw $this->createNotFoundException("Email does exist in session");  
            }
            $dataUser['email'] = $session->get('email');

            if ($session->has('phone')) {
                $dataUser['phone'] = $session->get('phone');
            }

            return array(
                    'adProperties' => $adProperties,
                    'user'         => $dataUser
            );
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