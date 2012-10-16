<?php

namespace LeDjassa\AdsBundle\Services;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use LeDjassa\AdsBundle\Model\Ad;
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

    	$urlShow = $this->router->generate('ad_show', array('idAd' => $ad->getId()), true);
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

        $this->sendEmailMessage($rendered, $subject, $this->parameters["email"]["noreply"], $ad->getUserEmail());
    }

    public function sendInterestedUserEmailMessage(Ad $ad, InterestedUser $interestedUser)
    {
        $subject = sprintf("Votre annonce \"%s\" interesse %s",
            $ad->getTitle(),
            $interestedUser->getName()
        );

        $template = $this->parameters['template']['interested_user.contact'];
        $rendered = $this->templating->render($template, array(
            'ad'       => $ad->getProperties(),
            'message'  => $interestedUser->getMessage(),
            'urlShow'  => $urlShow = $this->router->generate('ad_show', array('idAd' => $ad->getId()), true),
            'name'     => $interestedUser->getName(),
            'email'    => $interestedUser->getEmail(),
            'phone'    => $interestedUser->getPhone(),
        ));

        $this->sendEmailMessage($rendered, $subject, $interestedUser->getEmail(), $ad->getUserEmail());
    }

    public function sendContactEmailMessage(InformationUser $informationUser)
    {
        $template = $this->parameters['template']['contact'];
        $rendered = $this->templating->render($template, array(
            'message'  => $informationUser->getMessage(),
            'name'     => $informationUser->getName(),
            'email'    => $informationUser->getEmail(),
        ));

        $this->sendEmailMessage($rendered, $informationUser->getSubject(), $informationUser->getEmail(), $this->parameters["email"]["contact"]);
    }

    protected function sendEmailMessage($body, $subject, $fromEmail, $toEmail)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($body);

        $this->mailer->send($message);
    }
} 