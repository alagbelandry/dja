<?php

namespace LeDjassa\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Form\Type\AdType;
use LeDjassa\AdsBundle\Form\Type\PictureAdType;
use eDjassa\AdsBundle\Form\Type\PictureAd;
use LeDjassa\AdsBundle\Form\Type\AdDeleteType;
use LeDjassa\AdsBundle\Form\Type\AdSearchType;
use LeDjassa\AdsBundle\Form\Type\AdEditType;
use LeDjassa\AdsBundle\Model\AdQuery;
use LeDjassa\AdsBundle\Model\PictureAdQuery;
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
        $adsCollectionCriteria = AdQuery::create()
            ->filterByStatut(Ad::STATUT_CREATED)
            ->lastCreatedFirst();

        $adsCollection = $adsCollectionCriteria->find();
        $adProperties = array();
        foreach ($adsCollection as $ad)  {
            $adProperties [$ad->getId()] = $ad->getProperties();
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $adsCollectionCriteria,
            $this->get('request')->query->get('page', 1),
            5
        );
    
        return $this->render('LeDjassaAdsBundle:Ad:list.html.twig', array(
            'ads'           => $pagination,
            'adProperties' => $adProperties
        ));
    }

    /**
    * @Route("/rechercher", name="ad_search")
    * @Template()
    * @param string $criteria criteria
    */
    public function searchAction()
    {
        $form = $this->createForm(new AdSearchType());
        $request = $this->get('request');

        if ('POST' == $request->getMethod()) {
            // Bind value with form
            $form->bindRequest($request);

            if ($form->isValid()) {

                $criteria = $form->getData();
                $title = $criteria['title'];
                $category = $criteria['category'];
                $area = $criteria['area'];

                $adsCollectionCriteria = AdQuery::create()
                    ->filterByStatut(Ad::STATUT_CREATED)
                    ->lastCreatedFirst();

                $adsCollection = $adsCollectionCriteria->find();
                $adProperties = array();
                foreach ($adsCollection as $ad)  {
                    $adProperties [$ad->getId()] = $ad->getProperties();
                }

                $paginator = $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $adsCollectionCriteria,
                    $request->query->get('page', 1),
                    5
                );
            
                return $this->render('LeDjassaAdsBundle:Ad:list.html.twig', array(
                    'ads'           => $pagination,
                    'adProperties'  => $adProperties
                ));
            }

        } elseif ('GET' == $request->getMethod()) {

            return array(
                'form'  => $form->createView(), 
            );

        } else {
            throw new Exception("An error occurs during edit ad action");
        }
    }

    /**
    * @Route("/afficher/{idAd}", name="ad_show")
    * @Template()
    * @param int $idAd ad identifier
    */
    public function showAction($idAd)
    {   
        $ad = $this->getAd($idAd);

        return $this->render('LeDjassaAdsBundle:Ad:show.html.twig', array(
            'ad' => $ad->getProperties()
        ));
    }
    
   /**
    * @Route("/modifier/{idAd}", name="ad_edit")
    * @Template()
    * @param int $idAd ad identifier
    */
    public function editAction($idAd)
    {   
        $ad = $this->getAd($idAd);
        $form = $this->createForm(new AdEditType(), $ad);
        
        $request = $this->get('request');
        $formHandler = new AdEditHandler($form, $request, $this->get('password_encoder'));
        $process = $formHandler->process();

        if ($process == AdEditHandler::AD_SAVE_SUCCESS_STATUT) {

            return $this->render('LeDjassaAdsBundle:Ad:editSuccess.html.twig', array(
                'ad' => $ad->getProperties()
            ));

        } elseif ($process == AdEditHandler::INVALID_PASSWORD_STATUT) {

            return array(
                'form' => $form->createView(), 
                'ad' => $ad->getProperties(), 
                'isInvalidPassword' => true
            );

        } elseif ('GET' === $request->getMethod()) {

            return array(
                'form'  => $form->createView(), 
                'ad' => $ad->getProperties()
            );

        } else {
            throw new Exception("An error occurs during edit ad action");
        }
    }

   /**
    * @Route("/supprimer/{idAd}", name="ad_delete")
    * @Template()
    * @param int $idAd ad identifier
    */
    public function deleteAction($idAd)
    {   
        $ad = $this->getAd($idAd);

        $request = $this->get('request');

        $form = $this->createForm(new AdDeleteType());
        $formHandler = new AdDeleteHandler($form, $request, $ad, $this->get('password_encoder'));
        $process = $formHandler->process();

        if ($process == AdDeleteHandler::AD_DELETE_SUCCESS_STATUT) {

            return $this->render('LeDjassaAdsBundle:Ad:deleteSuccess.html.twig');
             
        } elseif ($process == AdDeleteHandler::INVALID_PASSWORD_STATUT) {

            return array(
                'form' => $form->createView(), 
                'ad' => $ad->getProperties(), 
                'isInvalidPassword' => true
            );

        } elseif ('GET' === $request->getMethod()) {

            return array(
                'form' => $form->createView(),
                'ad' => $ad->getProperties()
            );

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

        $formHandler = new AdAddHandler($form, $request, $this->get('password_encoder'), $this->get('ledjassa.mailer'));
        $process = $formHandler->process();

        if ($process) {

            return $this->render('LeDjassaAdsBundle:Ad:addSuccess.html.twig', array(
                'ad' => $ad->getProperties()
            ));

        } elseif ('GET' === $request->getMethod()) {

            return array(
                'form' => $form->createView()
            );
            
        } else {
            throw new Exception("An error occurs during add ad action");
        }
    }

    /**
    * Delete picture of ad
    * @return Response true if success otherwise false
    * @Route("/supprimerPhotos", name="picture_delete")
    *
    */
    public function pictureDeleteAction()
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

    /**
     * Get ad
     * @param int $id ad identifier
     * @return Ad $ad ad
     */
    public function getAd($id) {
        $ad = AdQuery::create()
                ->findOneById($id);

        if (!$ad instanceof Ad) {
            throw $this->createNotFoundException('Ad not found!');
        }
        return $ad;
    }
}