<?php

namespace LeDjassa\AdsBundle\Services;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\AdType;
use LeDjassa\AdsBundle\Model\InterestedUser;
use LeDjassa\AdsBundle\Model\InformationUser;

class Mailer
{
    protected $mailer;
    protected $router;
    protected $templating;
    protected $parameters;

    public function __construct($mailer, RouterInterface $router, EngineInterface $templating, array $parameters)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->parameters = $parameters;
    }

    public function sendConfirmAdCreatedEmailMessage(Ad $ad, $plainTextPassword)
    {
        $subject = "Votre annonce ". $ad->getTitle() ." est en ligne";

        $nameAdShow = $ad->getAdType()->getId() == AdType::ID_OFFERS ? 'ad_show_offers' : 'ad_show_demands';
        $urlShow = $this->router->generate($nameAdShow, array(
            'idAd'         => $ad->getId(),
            'slugCategory' => $ad->getCategory()->getSlug(),
            'slugAd'       => $ad->getSlug()
            ),
            true
        );

        $urlEdit = $this->router->generate('ad_edit', array('idAd' => $ad->getId()), true);
        $urlDelete = $this->router->generate('ad_delete', array('idAd' => $ad->getId()), true);

        $template = $this->parameters['template']['ad_created_confirmation'];
        $rendered = $this->templating->render($template, array(
            'ad' 	  			 => $ad,
            'plainTextPassword'  => $plainTextPassword,
            'urlShow' 			 => $urlShow,
            'urlEdit'   		 => $urlEdit,
            'urlDelete' 		 => $urlDelete,
        ));

        $this->sendEmailMessage($rendered, $subject, $this->parameters["email"]["noreply"], $ad->getUserEmail(), "Auportdadjame.com");
    }

    public function sendInterestedUserEmailMessage(Ad $ad, InterestedUser $interestedUser)
    {
        $subject = sprintf("Votre annonce \"%s\" interesse %s",
            $ad->getTitle(),
            $interestedUser->getName()
        );

        $nameAdShow = $ad->getAdType()->getId() == AdType::ID_OFFERS ? 'ad_show_offers' : 'ad_show_demands';
        $urlShow = $this->router->generate($nameAdShow, array(
            'idAd'         => $ad->getId(),
            'slugCategory' => $ad->getCategory()->getSlug(),
            'slugAd'       => $ad->getSlug()
            ),
            true
        );

        $template = $this->parameters['template']['interested_user.contact'];

        $nameAdShow = $ad->getAdType()->getId() == AdType::ID_OFFERS ? 'ad_show_offers' : 'ad_show_demands';
        $rendered = $this->templating->render($template, array(
            'ad'       => $ad->getProperties(),
            'message'  => $interestedUser->getMessage(),
            'urlShow'  => $urlShow,
            'name'     => $interestedUser->getName(),
            'email'    => $interestedUser->getEmail(),
            'phone'    => $interestedUser->getPhone(),
        ));

        $this->sendEmailMessage($rendered, $subject, $this->parameters["email"]["noreply"], $ad->getUserEmail(), "Auportdadjame.com");
    }

    public function sendContactEmailMessage(InformationUser $informationUser)
    {
        $template = $this->parameters['template']['contact'];
        $rendered = $this->templating->render($template, array(
            'message'  => $informationUser->getMessage(),
            'name'     => $informationUser->getName(),
            'email'    => $informationUser->getEmail(),
        ));

        $this->sendEmailMessage($rendered, $informationUser->getSubject(), $this->parameters["email"]["noreply"], $this->parameters["email"]["contact"], "Auportdadjame.com");
    }

    public function sendNewPasswordUserEmailMessage($plainTextPassword, $ad)
    {
        $subject = sprintf("Votre nouveau mot de passe pour votre annonce %s",
            $ad->getTitle()
        );

        $template = $this->parameters['template']['user.password_forgot'];
        $rendered = $this->templating->render($template, array(
            'ad'                => $ad,
            'plainTextPassword' => $plainTextPassword,
        ));

        $this->sendEmailMessage($rendered, $subject, $this->parameters["email"]["noreply"], $ad->getUserEmail(), "Auportdadjame.com");
    }

    public function sendConfirmAdEditedEmailMessage($ad)
    {
        $subject = sprintf("Suppression de votre annonce : %s",
            $ad->getTitle()
        );

        $template = $this->parameters['template']['ad_edited_confirmation'];
        $rendered = $this->templating->render($template, array(
            'ad'                => $ad,
        ));

        $this->sendEmailMessage($rendered, $subject, $this->parameters["email"]["noreply"], $ad->getUserEmail(), "Auportdadjame.com");
    }

    protected function sendEmailMessage($body, $subject, $fromEmail, $toEmail, $fromEmailName)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(array($fromEmail => $fromEmailName))
            ->setTo($toEmail)
            ->setBody($body);

        $this->mailer->send($message);
    }
}
