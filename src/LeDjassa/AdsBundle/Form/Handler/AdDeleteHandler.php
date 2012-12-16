<?php

namespace LeDjassa\AdsBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Services\Mailer;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
* The AdDeleteHandler.
* Use for manage your form submitions for update or delete ad
*
* @author Landry Alagbe
*/
class AdDeleteHandler
{
    const ERROR_PROCESSING_STATUT = 0;
    const AD_DELETE_SUCCESS_STATUT = 1;
    const INVALID_PASSWORD_STATUT = 2;

    protected $request;
    protected $form;
    protected $ad;
    protected $encoder;
    protected $mailer;

    /**
    * Initialize the handler with the form and the request
    *
    * @param Form $form
    * @param Request $request
    * @param Ad $ad
    * @param MessageDigestPasswordEncoder $encoder
    * @param Mailer $mailer
    *
    */
    public function __construct(Form $form, Request $request, Ad $ad, MessageDigestPasswordEncoder $encoder, Mailer $mailer)
    {
        $this->form = $form;
        $this->request = $request;
        $this->ad = $ad;
        $this->encoder = $encoder;
        $this->mailer  = $mailer;
    }

    /**
    * Process form
    *
    * @return int statut process
    */
    public function process()
    {
        // Check the method
        if ('POST' == $this->request->getMethod()) {
            // Bind value with form
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {
                return $this->onSuccess($this->form->getData());
            }
     }

        return self::ERROR_PROCESSING_STATUT;
    }

    /**
    * Check if password entry by user is valid
    * @param array $data contain password entry
    * @return boolean if password is valid
    */
    protected function onSuccess($data)
    {
        if (!$this->ad instanceof Ad) {
            throw new Exception("Ad not found !");
        }

        if ($this->encoder->isPasswordValid($this->ad->getUserPassword(), $data['user_password'], $this->ad->getUserSalt())) {
            $this->ad->setStatut(Ad::STATUT_DELETED)
               ->save();

            // send confirmation email
            //$this->mailer->sendConfirmAdEditedEmailMessage($this->ad);
            return self::AD_DELETE_SUCCESS_STATUT;
        }

        return self::INVALID_PASSWORD_STATUT;
    }
}
