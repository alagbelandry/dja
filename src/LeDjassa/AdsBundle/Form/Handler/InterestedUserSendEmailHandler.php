<?php

namespace LeDjassa\AdsBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\InterestedUser;
use LeDjassa\AdsBundle\Services\Mailer;

/**
* The InterestedUserSendEmailHandler.
* Use for manage send email by interesed user
*
* @author Landry Alagbe
*/
class InterestedUserSendEmailHandler
{
    protected $request;
    protected $form;
    protected $mailer;
    protected $ad;

    /**
    * Initialize the handler with the form and the request
    *
    * @param Form $form
    * @param Request $request
    * @param Mailer $mailer
    *
    */
    public function __construct(Form $form, Request $request, Ad $ad, Mailer $mailer)
    {
        $this->form = $form;
        $this->request = $request;
        $this->mailer  = $mailer;
        $this->ad  = $ad;
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

             $interestedUser = $this->form->getData();
             $this->onSuccess($interestedUser);

             return true;
        }
     }

    return false;
    }

    /**
    * Sending email and save message
    *
    * @param array $interestedUser
    *
    */
    protected function onSuccess(InterestedUser $interestedUser)
    {
        $interestedUser->setIpAdress($this->request->getClientIp());
        $interestedUser->save();

        // send email
        $this->mailer->sendInterestedUserEmailMessage($this->ad, $interestedUser);
    }
}
