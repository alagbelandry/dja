<?php

namespace LeDjassa\AdsBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\QuarterQuery;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
* The AdEditHandler.
* Use for manage your form submitions for update or delete ad
*
* @author Landry Alagbe
*/
class AdEditHandler
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
                return $this->onSuccess($this->form->getData());
            }
     }
        return false;
    }

    /**
    * Check if password entry by user is valid and save form
    * @param array $data ad object
    * @return boolean if password is valid
    */
    protected function onSuccess($ad)
    {
        if (!$ad instanceof Ad) {
            throw new Exception("Ad not found !");
        }

        $ad->save();
        return true;
    }
}