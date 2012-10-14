<?php

namespace LeDjassa\AdsBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\City;
use LeDjassa\AdsBundle\Model\Quarter;
use LeDjassa\AdsBundle\Model\CityQuery;
use LeDjassa\AdsBundle\Model\QuarterQuery;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
* The AdManageAccessHandler.
* Use for manage your form submitions for update or delete ad
*
* @author Landry Alagbe
*/
class AdEditHandler
{
    const ERROR_PROCESSING_STATUT = 0;
    const AD_SAVE_SUCCESS_STATUT = 1;
    const INVALID_PASSWORD_STATUT = 2;

    protected $request;
    protected $form;
    protected $encoder;

    /**
    * Initialize the handler with the form and the request
    *
    * @param Form $form
    * @param Request $request
    *
    */
    public function __construct(Form $form, Request $request, MessageDigestPasswordEncoder $encoder)
    {
        $this->form = $form;
        $this->request = $request;
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

                $parameters = $this->request->get('ad_edit');
                $isPasswordInvalid = !$this->onSuccess($this->form->getData(), $parameters['user_password']);
                if ($isPasswordInvalid) {
                    return self::INVALID_PASSWORD_STATUT;
                }
                return self::AD_SAVE_SUCCESS_STATUT;
            }
     }
        return self::ERROR_PROCESSING_STATUT;
    }

    /**
    * Check if password entry by user is valid and save form
    * @param array $data ad object
    * @param string $passwordEntry password entry
    * @return boolean if password is valid
    */
    protected function onSuccess($ad, $passwordEntry)
    {
        if (!$ad instanceof Ad) {
            throw new Exception("Ad not found !");
        }

        if ($this->encoder->isPasswordValid($ad->getUserPassword(), $passwordEntry, $ad->getUserSalt())) {
            $ad->save();
            return true;
        }

        return false;
    }
}