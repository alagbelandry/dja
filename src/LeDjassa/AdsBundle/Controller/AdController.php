<?php

namespace LeDjassa\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\AdType as TypeAd;
use LeDjassa\AdsBundle\Form\Type\AdType;
use LeDjassa\AdsBundle\Form\Type\AdDeleteType;
use LeDjassa\AdsBundle\Form\Type\AdAcessEditType;
use LeDjassa\AdsBundle\Form\Type\AdSearchType;
use LeDjassa\AdsBundle\Form\Type\AdEditType;
use LeDjassa\AdsBundle\Form\Type\PasswordForgotType;
use LeDjassa\AdsBundle\Model\AdQuery;
use LeDjassa\AdsBundle\Model\CategoryQuery;
use LeDjassa\AdsBundle\Model\AreaQuery;
use LeDjassa\AdsBundle\Form\Handler\AdAddHandler;
use LeDjassa\AdsBundle\Form\Handler\AdDeleteHandler;
use LeDjassa\AdsBundle\Form\Handler\AdEditHandler;
use LeDjassa\AdsBundle\Form\Handler\AdAcessEditHandler;
use LeDjassa\AdsBundle\Form\Handler\PasswordForgotHandler;

/**
 * @Route("/")
 */
class AdController extends Controller
{
    /**
    * @Route("", name="ad_list")
    * @Route("annonces")
    * @Route("annonces/offres", name="ad_list_offers")
    * @Route("annonces/demandes", name="ad_list_demands")
    * @Template()
    */
    public function indexAction()
    {
        $adsCollectionCriteria = AdQuery::create()->filterByLive()->lastCreatedFirst();

        $routeName = $this->get('request')->get('_route');
        if ($routeName == 'ad_list_offers') {
           $adsCollectionCriteria->filterByAdTypeId(TypeAd::ID_OFFERS);
        } elseif ($routeName == 'ad_list_demands') {
           $adsCollectionCriteria->filterByAdTypeId(TypeAd::ID_DEMANDS);
        } else {
            // no need more filter
        }

        $paginator = $this->get('ledjassa.paginator');
        $page = $this->get('request')->query->get('page', 1);
        $limit = $this->container->getParameter('limit_ads');

        return $this->render('LeDjassaAdsBundle:Ad:list.html.twig', array(
            'ads'           => $paginator->getPagination($adsCollectionCriteria, $page, $limit),
            'adProperties'  => $adsCollectionCriteria->getProperties(),
            'nbAdsOnline'   => $adsCollectionCriteria->count(),
            'nbAdsTotal'    => AdQuery::create()->count(),
        ));
    }

    /**
    * @Route("annonces/chercher/{categoryTitle}+{areaName}", name="ad_search")
    * @Template()
    * @param string $categoryTitle criteria on category
    * @param string $areaName criteria on area
    */
    public function searchAction($categoryTitle = false, $areaName = false)
    {
        $query = $this->get('request')->query;
        $title = $query->get('title');

        $category = CategoryQuery::create()->findOneBySlug($categoryTitle);
        $area = AreaQuery::create()->findOneBySlug($areaName);

        $adsCollectionCriteria = AdQuery::create()
            ->filterByLive()
            ->searchByCategoryAndAreaAndTitleOrDescription($category, $area, '%'.$title.'%')
            ->lastCreatedFirst();

        $paginator = $this->get('ledjassa.paginator');
        $page = $query->get('page', 1);
        $limit = $this->container->getParameter('limit_ads');

        return $this->render('LeDjassaAdsBundle:Ad:search.html.twig', array(
            'ads'                   => $paginator->getPagination($adsCollectionCriteria, $page, $limit),
            'adProperties'          => $adsCollectionCriteria->getProperties(),
            'categorieKeySearch'    => !empty($category) ? $category->getTitle() : '',
            'areaKeySearch'         => !empty($area) ? $area->getName() : '',
            'titleKeySearch'        => $title,
            'nbAdsFound'            => $adsCollectionCriteria->count()
        ));
    }

    /**
    * @Route("formulaireRechercher", name="ad_search_form")
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
                        'categoryTitle' => empty($criteria['category']) ? 'toutes-categories' : $criteria['category']->getSlug(),
                        'areaName'      => empty($criteria['area']) ? 'toutes-regions' : $criteria['area']->getSlug(),
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
    * @Route("annonces/offres/{slugCategory}+{slugAd}-{idAd}", requirements={"slugAd" = "[a-zA-Z0-9-_/]+", "slugCategory" = "[a-zA-Z0-9-_/]+"}, name="ad_show_offers")
    * @Route("annonces/demandes/{slugCategory}+{slugAd}-{idAd}", requirements={"slugAd" = "[a-zA-Z0-9-_/]+", "slugCategory" = "[a-zA-Z0-9-_/]+"}, name="ad_show_demands")
    * @ParamConverter("ad", class="LeDjassa\AdsBundle\Model\Ad", options={"mapping"={"idAd":"id"}, "exclude"={"slugCategory", "slugAd"}})
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
    * @Route("annonces/modifier/{idAd}", name="ad_edit")
    * @ParamConverter("ad", class="LeDjassa\AdsBundle\Model\Ad", options={"mapping"={"idAd":"id"}})
    * @Template()
    * @param Ad $ad
    */
    public function editAction(Ad $ad)
    {
        if (!$ad->isLive()) {
            return $this->render('LeDjassaAdsBundle:Ad:notFound.html.twig');
        }

        $request = $this->get('request');
        $cookieAdSecureValue = $request->cookies->get('ad_secure');
        $isValidCookieAdSecureValue = !empty($cookieAdSecureValue) && $cookieAdSecureValue == $ad->getId();;
        if (!$isValidCookieAdSecureValue) {
            return $this->redirect(
                    $this->generateUrl('ad_access_edit', array(
                        'idAd' => $ad->getId()
                    )),
                    301
                );
        }

        $form = $this->createForm(new AdEditType(), $ad);

        $formHandler = new AdEditHandler($form, $request);
        $process = $formHandler->process();

        if ($process) {
            return $this->render('LeDjassaAdsBundle:Ad:editSuccess.html.twig', array(
                'ad' => $ad->getProperties()
            ));

        } elseif ('GET' === $request->getMethod()) {
            return array(
                'form'  => $form->createView(),
                'ad'    => $ad->getProperties()
            );

        } else {
            throw new Exception("An error occurs during edit ad action");
        }
    }

   /**
    * @Route("annonces/acces-modifications/{idAd}", name="ad_access_edit")
    * @ParamConverter("ad", class="LeDjassa\AdsBundle\Model\Ad", options={"mapping"={"idAd":"id"}})
    * @Template()
    * @param Ad $ad Ad
    */
    public function accessEditAction(Ad $ad)
    {
        if (!$ad->isLive()) {
            return $this->render('LeDjassaAdsBundle:Ad:notFound.html.twig');
        }

        $request = $this->get('request');

        $form = $this->createForm(new AdAcessEditType());
        $formHandler = new AdAcessEditHandler($form, $request, $ad, $this->get('password_encoder'));
        $process = $formHandler->process();

        if ($process == AdAcessEditHandler::AD_DELETE_SUCCESS_STATUT) {

            $response = new RedirectResponse($this->generateUrl('ad_edit', array(
                'idAd' => $ad->getId(),
                )),
                301
            );
            $response->headers->setCookie(new Cookie('ad_secure', $ad->getId()));

            return $response;

        } elseif ($process == AdAcessEditHandler::INVALID_PASSWORD_STATUT) {
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
    * @Route("annonces/supprimer/{idAd}", name="ad_delete")
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
    * @Route("annonces/ajouter", name="ad_add")
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
    * @Route("recupererMotDePasse/{idAd}", name="password_forgot")
    * @ParamConverter("ad", class="LeDjassa\AdsBundle\Model\Ad", options={"mapping"={"idAd":"id"}})
    * @Template()
    * @param Ad $ad
    */
    public function passwordForgotAction(Ad $ad)
    {
        if (!$ad->isLive()) {
            return $this->render('LeDjassaAdsBundle:Ad:notFound.html.twig');
        }

        $request = $this->get('request');
        $form = $this->createForm(new PasswordForgotType());
        $formHandler = new PasswordForgotHandler($form, $request, $ad, $this->get('ledjassa.mailer'), $this->get('password_encoder'));
        $process = $formHandler->process();

        if ($process == PasswordForgotHandler::PASSWORD_SEND_SUCCESS_STATUT) {
             return $this->render('LeDjassaAdsBundle:Ad:forgotPasswordSuccess.html.twig');

        } elseif ($process == PasswordForgotHandler::INVALID_EMAIL_STATUT) {
            return array(
                'form'           => $form->createView(),
                'ad'             => $ad->getProperties(),
                'isInvalidEmail' => true
            );

        } elseif ('GET' === $request->getMethod()) {
            return array(
                'form' => $form->createView(),
                'ad'   => $ad->getProperties()
            );

        } else {
            throw new Exception("An error occurs during password forgot action");
        }
    }
}
