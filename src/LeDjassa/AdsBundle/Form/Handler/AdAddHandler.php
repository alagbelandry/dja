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
* The AdAddHandler.
* Use for manage your form submitions for add ad
*
* @author Landry Alagbe
*/
class AdAddHandler
{
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
    * @return boolean
    */
    public function process()
    {
        // Check the method
        if ('POST' == $this->request->getMethod()) {
            // Bind value with form
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {

             $ad = $this->form->getData();
             $this->onSuccess($ad);

             return true;
        }
     }

    return false;
    }

    /**
    * Link object to ad and save
    *
    * @param array $ad
    *
    */
    protected function onSuccess(Ad $ad)
    {
        $city = $this->getCity($ad);
        $ad->setCity($city);

        // link quarter and city
        if ($city instanceof City) {

            $quarter = $this->getQuarter();
            if ($quarter instanceof Quarter) {

                $quarter->setCity($city)
                 ->save();
            }
        }

        // secure ad with a password : Todo changes this method it's maybe bad, way: implements encoder like service
        $ad->setUserSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
        $ad->setUserPassword($this->encoder->encodePassword($ad->getUserPassword(), $ad->getUserSalt()));

        // set ip adress
        $ad->setUserIpAdress($this->request->getClientIp());

        $ad->save();
    }

    /**
    * Get city select in form
    * @return City city select
    */
    function getCity(Ad $ad)
    {
        $parameters = $this->request->get('ad');
        $idCity = isset($parameters['city']['name']) ? $parameters['city']['name'] : '';

        return CityQuery::create()
                ->findOneById($idCity);
    }

    /**
    * Get new quarter depends of name quarter parameter of form
    * @return Quarter|false new quarter or false if it's not
    */
    function getQuarter()
    {
        $parameters = $this->request->get('ad');
        $nameQuarter = isset($parameters['city']['quarter']['name']) ? ucfirst($parameters['city']['quarter']['name']) : '';

        if (empty($nameQuarter)) {
            return false;
        }

        $quarter = QuarterQuery::create()
                      ->findOneByName($nameQuarter);

        // not a new quarter name
        if ($quarter instanceof Quarter) {
            return false;
        }
                
        $quarter = new Quarter();
        $quarter->setName($nameQuarter);

        return $quarter;
    }
}