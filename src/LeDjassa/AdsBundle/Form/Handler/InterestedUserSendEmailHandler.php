<?php

namespace LeDjassa\AdsBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use LeDjassa\AdsBundle\Model\InterestedUser;
use Symfony\Component\Config\Definition\Exception\Exception;

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

    /**
    * Initialize the handler with the form and the request
    *
    * @param Form $form
    * @param Request $request
    *
    */
    public function __construct(Form $form, Request $request)
    {
        $this->form = $form;
        $this->request = $request;
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
    }
}