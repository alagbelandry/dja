<?php

namespace LeDjassa\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Form\Type\AdType;

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
    	$request = $this->get('request');
        $ad = new Ad();
        $form = $this->createForm(new AdType(), $ad);

        if ('POST' === $request->getMethod()) {
        	$form->bindRequest($request);

        	if ($form->isValid()) {
        		$ad->save();
        	}

        	//return $this->redirect($this->generateUrl('ad_success'));
        }
        
        return  array('form' => $form->createView(), 'ad' => $ad);
		
    }
}