<?php

namespace LeDjassa\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class InformationSiteController extends Controller
{
    /**
    * @Route("/informations-legales", name="legal_info")
    * @Template()
    */
    public function legalInfoAction()
    {
        return $this->render('LeDjassaAdsBundle:InformationSite:legalInfos.html.twig');
    }

    /**
    * @Route("/conditions-generales-de-vente", name="cgv")
    * @Template()
    */
    public function cgvAction()
    {
        return $this->render('LeDjassaAdsBundle:InformationSite:cgv.html.twig');
    }

    /**
    * @Route("/regles-de-fonctionnement", name="operating_rules")
    * @Template()
    */
    public function operatingRulesAction()
    {
        return $this->render('LeDjassaAdsBundle:InformationSite:operatingRules.html.twig');
    }

    /**
    * @Route("/limites-de-responsabilite", name="limit_liability")
    * @Template()
    */
    public function limitLiabilityAction()
    {
        return $this->render('LeDjassaAdsBundle:InformationSite:limitLiability.html.twig');
    }

    /**
    * @Route("/qui-sommes-nous", name="who_we_are")
    * @Template()
    */
    public function whoWeAreAction()
    {
        return $this->render('LeDjassaAdsBundle:InformationSite:whoWeAre.html.twig');
    }

}
