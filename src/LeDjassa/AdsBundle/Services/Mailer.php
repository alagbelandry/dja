<?php

namespace LeDjassa\AdsBundle\Services;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;

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

    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail)
    {
        $subject = '';
        $body = '';

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($body);

        $this->mailer->send($message);
    }
} 