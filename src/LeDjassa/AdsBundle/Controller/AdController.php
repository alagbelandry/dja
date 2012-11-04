<?php

namespace LeDjassa\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
use LeDjassa\AdsBundle\Model\CategoryQuery;
use LeDjassa\AdsBundle\Model\AreaQuery;
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
            ->filterByLive()
            ->lastCreatedFirst();

        $adsCollection = $adsCollectionCriteria->find();
        $adProperties = array();
        foreach ($adsCollection as $ad)  {
            $adProperties [$ad->getId()] = $ad->getProperties();
        }

        $paginator = $this->get('ledjassa.paginator');
        $page = $this->get('request')->query->get('page', 1);
        $limit = $this->container->getParameter('limit_ads');
        
        $pagination = $paginator->getPagination($adsCollectionCriteria, $page, $limit);
    
        return $this->render('LeDjassaAdsBundle:Ad:list.html.twig', array(
            'ads'           => $pagination,
            'adProperties'  => $adProperties
        ));
    }

    /**
    * @Route("/rechercher/{categoryTitle}/{areaName}", name="ad_search")
    * @Template()
    * @param string $categoryTitle criteria on category
    * @param string $areaName criteria on area
    */
    public function searchAction($categoryTitle, $areaName)
    {   
        $query = $this->get('request')->query;

        $title = $query->get('title');
        $category = CategoryQuery::create()->findOneByTitle($categoryTitle);
        $area = AreaQuery::create()->findOneByName($areaName);

        $adsCollectionCriteria = AdQuery::create()
            ->filterByLive()
            ->searchByCategoryAndAreaAndTitleOrDescription($category, $area, '%'.$title.'%')
            ->lastCreatedFirst();

        $adsCollection = $adsCollectionCriteria->find();
        $adProperties = array();
        foreach ($adsCollection as $ad)  {
            $adProperties [$ad->getId()] = $ad->getProperties();
        }

        $paginator = $this->get('ledjassa.paginator');
        $page = $query->get('page', 1);
        $limit = $this->container->getParameter('limit_ads');

        $pagination = $paginator->getPagination($adsCollectionCriteria, $page, $limit);

        return $this->render('LeDjassaAdsBundle:Ad:search.html.twig', array(
            'ads'           => $pagination,
            'adProperties'  => $adProperties,
        ));
    }

    /**
    * @Route("/formulaireRechercher", name="ad_search_form")
    * @Template()
    */
    public function searchFormAction()
    {
        $form = $this->createForm(new AdSearchType());
        $request = $this->get('request');

        if ('POST' == $request->getMethod()) {

            $form->bindRequest($request);

            if ($form->isValid()) {

                $criteria = $form->getData();
                return $this->redirect(
                    $this->generateUrl('ad_search', array(
                        'categoryTitle' => empty($criteria['category']) ? 'toutes-categories' : $criteria['category']->getTitle(),
                        'areaName'      => empty($criteria['area']) ? 'toutes-regions' : $criteria['area']->getName(),
                        'title'         => $criteria['title'],
                    )), 
                    301
                );
            }

        } elseif ('GET' == $request->getMethod()) {

            return array(
                'form'  => $form->createView(), 
            );

        } else {
            throw new Exception("An error occurs during search form action");
        }
    }

    /**
    * @Route("/afficher/{idAd}", name="ad_show")
    * @ParamConverter("ad", class="LeDjassa\AdsBundle\Model\Ad", options={"mapping"={"idAd":"id"}})
    * @Template()
    * @param Ad $ad ad
    */
    public function showAction(Ad $ad)
    {   
        if (!$ad->isLive()) {
            return $this->render('LeDjassaAdsBundle:Ad:notFound.html.twig');   
        }

        return $this->render('LeDjassaAdsBundle:Ad:show.html.twig', array(
            'ad' => $ad->getProperties()
        ));
    }
    
   /**
    * @Route("/modifier/{idAd}", name="ad_edit")
    * @ParamConverter("ad", class="LeDjassa\AdsBundle\Model\Ad", options={"mapping"={"idAd":"id"}})
    * @Template()
    * @param Ad $ad
    */
    public function editAction(Ad $ad)
    {   
        if (!$ad->isLive()) {
            return $this->render('LeDjassaAdsBundle:Ad:notFound.html.twig');   
        }

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
                'form'              => $form->createView(), 
                'ad'                => $ad->getProperties(), 
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
    * @ParamConverter("ad", class="LeDjassa\AdsBundle\Model\Ad", options={"mapping"={"idAd":"id"}})
    * @Template()
    * @param Ad $ad Ad
    */
    public function deleteAction(Ad $ad)
    {   
        if (!$ad->isLive()) {
            return $this->render('LeDjassaAdsBundle:Ad:notFound.html.twig');   
        }

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
}