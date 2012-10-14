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
    * @return boolean
    */
    public function process()
    {
        // Check the method
        if ('POST' == $this->request->getMethod()) {
            // Bind value with form
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {

                $isPasswordValid = $this->onSuccess($this->form->getData());
                return true;
            }
     }
        return false;
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

        $salt = $this->ad->getUserSalt();
        $password = $this->ad->getUserPassword();

        $encodedPasswordEntry =  $this->encoder->encodePassword($data['user_password'], $salt);

        return $this->encoder->isPasswordValid($encodedPasswordEntry, $password, $salt);
    }
}