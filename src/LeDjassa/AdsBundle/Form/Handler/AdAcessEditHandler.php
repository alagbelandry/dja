<?php

namespace LeDjassa\AdsBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use LeDjassa\AdsBundle\Model\Ad;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
* The AdAcessEditHandler.
* Use for manage your form submitions for update or delete ad
*
* @author Landry Alagbe
*/
class AdAcessEditHandler
{
    const ERROR_PROCESSING_STATUT = 0;
    const AD_DELETE_SUCCESS_STATUT = 1;
    const INVALID_PASSWORD_STATUT = 2;

    protected $request;
    protected $form;
    protected $ad;
    protected $encoder;

    /**
    * Initialize the handler with the form and the request
    *
    * @param Form $form
    * @param Request $request
    * @param Ad $ad
    * @param MessageDigestPasswordEncoder $encoder
    *
    */
    public function __construct(Form $form, Request $request, Ad $ad, MessageDigestPasswordEncoder $encoder)
    {
        $this->form = $form;
        $this->request = $request;
        $this->ad = $ad;
        $this->encoder = $encoder;
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

                $isPasswordInvalid = !$this->onSuccess($this->form->getData());
                if ($isPasswordInvalid) {
                    return self::INVALID_PASSWORD_STATUT;
                }

                return self::AD_DELETE_SUCCESS_STATUT;
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
            $this->request->cookies->set('ad_secure', $this->ad->getId());
            return true;
        }
        return false;
    }
}