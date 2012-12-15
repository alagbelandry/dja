<?php

namespace LeDjassa\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\InterestedUser;
use LeDjassa\AdsBundle\Form\Type\AdUserSendEmailType;
use LeDjassa\AdsBundle\Model\AdQuery;
use LeDjassa\AdsBundle\Form\Handler\InterestedUserSendEmailHandler;

/**
 * @Route("/annonces")
 */
class InterestedUserController extends Controller
{
    /**
    * @Route("/envoyer-email/{idAd}", name="ad_user_send_email")
    * @Template()
    * @param int $idAd ad identifier
    */
    public function sendEmailAction($idAd)
    {
        $ad = $this->getAd($idAd);

        $interestedUser = new InterestedUser();
        $interestedUser->setAd($ad);

        $form = $this->createForm(new AdUserSendEmailType(), $interestedUser);

        $request = $this->get('request');

        $formHandler = new InterestedUserSendEmailHandler($form, $request, $ad, $this->get('ledjassa.mailer'));
        $process = $formHandler->process();

        if ($process) {
            return $this->render('LeDjassaAdsBundle:InterestedUser:sendEmailSuccess.html.twig', array(
                'ad' => $ad->getProperties()
            ));

        } elseif ('GET' === $request->getMethod()) {
            return array(
                'form' => $form->createView(),
                'ad' => $ad->getProperties()
            );

        } else {
            throw new Exception("An error occurs during sending email");
        }
    }

    /**
     * Get ad
     * @param  int $id ad identifier
     * @return Ad  $ad ad
     */
    public function getAd($id)
    {
        $ad = AdQuery::create()
                ->findOneById($id);

        if (!$ad instanceof Ad) {
            throw $this->createNotFoundException('Ad not found!');
        }

        return $ad;
    }
}
