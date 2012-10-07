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

/**
 * The AdHandler.
 * Use for manage your form submitions
 *
 * @author Landry Alagbe
 */
class AdHandler
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
    	if ('POST' == $this->request->getMethod())
    	{
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

    	$ad->save();

        $isSaveFail = !$this->saveDataInSession($ad);

        if ($isSaveFail) {
            throw new Exception("Can't save data User in session");    
        }
    }

        /**
     * Save user data in session
     * @return boolean true if store sucess otherwise false 
     */
    function saveDataInSession(Ad $ad) 
    {
    	if (!$ad instanceof Ad) {
    		return false;
    	}
 
      	// init session
        $session = $this->request->getSession();
        $session->clear();

        $session->set('idAd',  $ad->getId());

        $parameters = $this->request->get('ad');
        if (!isset($parameters['user'])) {
            return false;
        }

        $isUserNameEmpty = !isset($parameters['user']['username']) || empty($parameters['user']['username']);
        if ($isUserNameEmpty) {
            throw new Exception("User name is empty in Form Ad");
        }
        $session->set('username',  $parameters['user']['username']);

        $isEmailEmpty = !isset($parameters['user']['email']) || empty($parameters['user']['email']);
        if ($isEmailEmpty) {
            throw new Exception("Email is empty in Form Ad"); 
        }
        $session->set('email',  $parameters['user']['email']);

        if (isset($parameters['user']['phone']) && !empty($parameters['user']['phone'])) {
            $session->set('phone',  $parameters['user']['phone']);
        }

        return true;
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
