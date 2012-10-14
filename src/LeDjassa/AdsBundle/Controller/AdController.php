<?php

namespace LeDjassa\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\City;
use LeDjassa\AdsBundle\Model\Quarter;
use LeDjassa\AdsBundle\Model\PictureAd;
use LeDjassa\AdsBundle\Form\Type\AdType;
use LeDjassa\AdsBundle\Form\Type\PictureAdType;
use LeDjassa\AdsBundle\Form\Type\AdDeleteType;
use LeDjassa\AdsBundle\Form\Type\AdEditType;
use LeDjassa\AdsBundle\Model\AdQuery;
use LeDjassa\AdsBundle\Model\CityQuery;
use LeDjassa\AdsBundle\Model\QuarterQuery;
use LeDjassa\AdsBundle\Model\UserTypeQuery;
use LeDjassa\AdsBundle\Model\AdTypeQuery;
use LeDjassa\AdsBundle\Form\Handler\AdAddHandler;
use LeDjassa\AdsBundle\Form\Handler\AdDeleteHandler;
use LeDjassa\AdsBundle\Form\Handler\AdEditHandler;

/**
 * @Route("/annonces")
 */
class AdController extends Controller
{
    /**
    * @Route("/", name="ad_list")
    * @Template()
    */
    public function indexAction()
    {   
        $adsCollection = AdQuery::create()
                        ->filterByStatut(Ad::STATUT_CREATED)
                        ->recentlyUpdated()
                        ->lastUpdatedFirst()
                        ->find();

        $ads = array();
        foreach ($adsCollection as $ad)  {
            $ads [] = $ad->getProperties();
        }
        
        return $this->render('LeDjassaAdsBundle:Ad:list.html.twig', array(
            'ads' => $ads
        ));
    }

    /**
    * @Route("/afficher/{idAd}", name="ad_show")
    * @Template()
    */
    public function showAction($idAd)
    {   
        $ad = AdQuery::create()
                ->findOneById($idAd);

        if (!$ad instanceof Ad) {
            throw $this->createNotFoundException('Ad not found!');
        }


        return $this->render('LeDjassaAdsBundle:Ad:show.html.twig', array(
            'ad' => $ad->getProperties()
        ));
    }
    
   /**
    * @Route("/modifier/{idAd}", name="ad_edit")
    * @Template()
    */
    public function editAction($idAd)
    {   
        // Todo : Factoriser dans les controlleur ou sa existe
        $ad = AdQuery::create()
                ->findOneById($idAd);

        if (!$ad instanceof Ad) {
            throw $this->createNotFoundException('Ad not found!');
        }

        $form = $this->createForm(new AdEditAccessType());
            
        $request = $this->get('request');

        $formHandler = new AdEditAccessHandler($form, $request, $ad, new MessageDigestPasswordEncoder('sha512', true, 10));
        $process = $formHandler->process();

        if ($process) {

            return $this->render('LeDjassaAdsBundle:Ad:editSuccess.html.twig');

        } elseif ('GET' === $request->getMethod()) {

            return array('form' => $form->createView(), 'ad' => $ad);

        } else {
            throw new Exception("An error occurs during edit ad action");
        }
    }

   /**
    * @Route("/supprimer/{idAd}", name="ad_delete")
    * @Template()
    */
    public function deleteAction($idAd)
    {   
        // Todo : Factoriser dans les controlleur ou sa existe
        $ad = AdQuery::create()
                ->findOneById($idAd);

        if (!$ad instanceof Ad) {
            throw $this->createNotFoundException('Ad not found!');
        }

        $request = $this->get('request');

        $form = $this->createForm(new AdDeleteType());
        $formHandler = new AdDeleteHandler($form, $request, $ad, new MessageDigestPasswordEncoder('sha512', true, 10));
        $process = $formHandler->process();

        if ($process == AdDeleteHandler::AD_DELETE_SUCCESS_STATUT) {

            return $this->render('LeDjassaAdsBundle:Ad:deleteSuccess.html.twig');
             
        } elseif ($process == AdDeleteHandler::INVALID_PASSWORD_STATUT) {

            return array('form' => $form->createView(), 'ad' => $ad->getProperties(), 'isInvalidPassword' => true);

        } elseif ('GET' === $request->getMethod()) {

            return array('form' => $form->createView(), 'ad' => $ad->getProperties());

        } else {

            throw new Exception("An error occurs during delete ad action");
        }
    }

 	/**
	* @Route("/ajouter", name="ad_add")
	* @Template()
	*/
    public function addAction()
    {
        $ad = new Ad();
        $form = $this->createForm(new AdType(), $ad);

        $request = $this->get('request');

        $formHandler = new AdAddHandler($form, $request, new MessageDigestPasswordEncoder('sha512', true, 10));
        $process = $formHandler->process();

        if ($process) {

            return $this->render('LeDjassaAdsBundle:Ad:addSuccess.html.twig', array(
                'ad' => $ad->getProperties()
            ));

        } elseif ('GET' === $request->getMethod()) {

            return array('form' => $form->createView(), 'ad' => $ad);

        } else {
            throw new Exception("An error occurs during add ad action");
        }
    }

}