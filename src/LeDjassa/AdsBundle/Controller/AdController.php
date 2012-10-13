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
use LeDjassa\AdsBundle\Form\Type\AdManageAccessType;
use LeDjassa\AdsBundle\Model\AdQuery;
use LeDjassa\AdsBundle\Model\CityQuery;
use LeDjassa\AdsBundle\Model\QuarterQuery;
use LeDjassa\AdsBundle\Model\UserTypeQuery;
use LeDjassa\AdsBundle\Model\AdTypeQuery;
use LeDjassa\AdsBundle\Form\Handler\AdAddHandler;
use LeDjassa\AdsBundle\Form\Handler\AdManageAccessHandler;

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
    * @Route("/gestion/{idAd}", name="ad_manage")
    * @Template()
    */
    public function manageAction($idAd)
    {   
        $ad = AdQuery::create()
                ->findOneById($idAd);

        if (!$ad instanceof Ad) {
            throw $this->createNotFoundException('Ad not found!');
        }

        $form = $this->createForm(new AdManageAccessType());
            
        $request = $this->get('request');
        $formHandler = new AdManageAccessHandler($form, $request, $ad, new MessageDigestPasswordEncoder('sha512', true, 10));
        $process = $formHandler->process();

        if ($process) {
            // set cookie to know if user is connect
            //var_dump("ok can access manage");
            return new RedirectResponse($this->generateUrl('ad_edit', array(
                'idAd' => $idAd
            )));

        } elseif ('GET' === $request->getMethod()) {

            return array('form' => $form->createView(), 'ad' => $ad->getProperties());

        } else {

            throw new Exception("Unknow Request Method in Form Manage Acess Ad");
        }
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

        $form = $this->createForm(new AdType(), $ad);

        $request = $this->get('request');

        $formHandler = new AdAddHandler($form, $request, new MessageDigestPasswordEncoder('sha512', true, 10));
        $process = $formHandler->process();

        if ($process) {

            return new RedirectResponse($this->generateUrl('ad_add_success', array(
                'idAd' => $ad->getId()
            )));

        } elseif ('GET' === $request->getMethod()) {

            return array('form' => $form->createView(), 'ad' => $ad);

        } else {
            throw new Exception("Unknow Request Method in Form Add Ad");
        }
    }

   /**
    * @Route("/supprimer/{idAd}", name="ad_delete")
    * @Template()
    */
    public function deleteAction($idAd)
    {   

    }

   /**
    * @Route("/supprimer/{idAd}", name="ad_edit_or_delete_success")
    * @Template()
    */
    public function editOrDeleteSuccessAction($idAd)
    {   
        $ad = AdQuery::create()
                ->findOneById($idAd)
                ->toArray();

        if (!is_array($ad) || empty($ad)) {
            throw $this->createNotFoundException('Ad not found!');
        }

        return $this->render('LeDjassaAdsBundle:Ad:editOrDeleteSuccess.html.twig', array(
            'ad' => $ad
        ));
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

            return new RedirectResponse($this->generateUrl('ad_add_success', array(
                'idAd' => $ad->getId()
            )));

        } elseif ('GET' === $request->getMethod()) {

            return array('form' => $form->createView(), 'ad' => $ad);

        } else {
            throw new Exception("Unknow Request Method in Form Add Ad");
        }
    }

    /**
    * @Route("/publier/{idAd}", name="ad_add_success")
    * @Method({"GET", "POST"})
    * @Template()
    */
    public function addSuccessAction($idAd)
    {   
        $ad = AdQuery::create()
                ->findOneById($idAd)
                ->toArray();

        if (!is_array($ad) || empty($ad)) {
            throw $this->createNotFoundException('Ad not found!');
        }

        return $this->render('LeDjassaAdsBundle:Ad:addSuccess.html.twig', array(
            'ad' => $ad
        ));
    }
}