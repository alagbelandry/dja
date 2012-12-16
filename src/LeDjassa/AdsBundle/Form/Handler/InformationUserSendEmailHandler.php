<?php

namespace LeDjassa\AdsBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use LeDjassa\AdsBundle\Model\InformationUser;
use LeDjassa\AdsBundle\Services\Mailer;

/**
* The InterestedUserSendEmailHandler.
* Use for manage send email contact by user
*
* @author Landry Alagbe
*/
class InformationUserSendEmailHandler
{
    protected $request;
    protected $form;
    protected $mailer;

    /**
    * Initialize the handler with the form and the request
    *
    * @param Form $form
    * @param Request $request
    * @param Mailer $mailer
    *
    */
    public function __construct(Form $form, Request $request, Mailer $mailer)
    {
        $this->form = $form;
        $this->request = $request;
        $this->mailer  = $mailer;
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

             $informationUser = $this->form->getData();
             $this->onSuccess($informationUser);

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
    protected function onSuccess(InformationUser $informationUser)
    {
        $informationUser->setIpAdress($this->request->getClientIp());
        $informationUser->save();

        // send email
        $this->mailer->sendContactEmailMessage($informationUser);
    }
}
