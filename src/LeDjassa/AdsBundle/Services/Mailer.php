<?php

namespace LeDjassa\AdsBundle\Services;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use LeDjassa\AdsBundle\Model\Ad;

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

    public function sendConfirmAdCreatedEmailMessage(Ad $ad)
    {
    	$subject = "Votre annonce ". $ad->getTitle() ." est en ligne";

    	$urlShow = $this->router->generate('ad_show', array('idAd' => $ad->getId()), true);
    	$urlEdit = $this->router->generate('ad_edit', array('idAd' => $ad->getId()), true);
    	$urlDelete = $this->router->generate('ad_delete', array('idAd' => $ad->getId()), true);

    	$template = $this->parameters['template']['ad_created_confirmation'];
    	$rendered = $this->templating->render($template, array(
            'ad' 	  	=> $ad,
            'urlShow'   => $urlShow,
            'urlEdit'   => $urlEdit,
            'urlDelete' => $urlDelete,
        ));

        $this->sendEmailMessage($rendered, $subject, $this->parameters['noreply_from_email'], $ad->getUserEmail());
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