<?php

namespace LeDjassa\AdsBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use LeDjassa\AdsBundle\Services\Mailer;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use LeDjassa\AdsBundle\Model\Ad;

/**
* The InterestedUserSendEmailHandler.
* Use for manage send email contact by user
*
* @author Landry Alagbe
*/
class PasswordForgotHandler
{
    const ERROR_PROCESSING_STATUT = 0;
    const PASSWORD_SEND_SUCCESS_STATUT = 1;
    const INVALID_EMAIL_STATUT = 2;

    protected $request;
    protected $form;
    protected $mailer;
    protected $ad;
    protected $encoder;

    /**
    * Initialize the handler with the form and the request
    *
    * @param Form $form
    * @param Request $request
    * @param Mailer $mailer
    * @param Ad $ad
    * @param MessageDigestPasswordEncoder $encoder
    *
    */
    public function __construct(Form $form, Request $request, Ad $ad, Mailer $mailer, MessageDigestPasswordEncoder $encoder)
    {
        $this->form = $form;
        $this->request = $request;
        $this->mailer  = $mailer;
        $this->ad = $ad;
        $this->encoder = $encoder;
    }

    /**
    * Process form
    *
    * @return boolean
    */
    public function process()
    {
        // Check the method
        if ('POST' == $this->request->getMethod()) {
            // Bind value with form
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {

             $emailEnter = $this->form->getData();
             return $this->onSuccess($emailEnter);
        }

        return self::ERROR_PROCESSING_STATUT;
     }

    return false;
    }

    /**
    * Check if email is valid and send new password to user
    *
    * @param string $email
    *
    */
    protected function onSuccess($emailEnter)
    {   
        if ($emailEnter != $this->ad->getUserEmail()) {
            return self::INVALID_EMAIL_STATUT;
        }

        // generate new password
        $plainTextPassword = '';

        $this->ad
            ->setUserPassword($this->encoder->encodePassword($plainTextPassword, $this->ad->getUserSalt()))
            ->save();

        $this->mailer->sendNewPasswordUserEmailMessage($plainTextPassword, $this->ad);
        return PASSWORD_SEND_SUCCESS_STATUT;
    }
}