<?php

namespace LeDjassa\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use LeDjassa\AdsBundle\Model\InformationUser;
use LeDjassa\AdsBundle\Form\Type\ContactType;
use LeDjassa\AdsBundle\Form\Handler\InformationUserSendEmailHandler;


class InformationUserController extends Controller
{
    /**
    * @Route("/contact", name="contact")
    * @Template()
    */
    public function sendEmailAction()
    {   
        $informationUser = new InformationUser();
        
        $form = $this->createForm(new ContactType(), $informationUser);

        $request = $this->get('request');

        $formHandler = new InformationUserSendEmailHandler($form, $request,$this->get('ledjassa.mailer'));
        $process = $formHandler->process();

        if ($process) {

            return $this->render('LeDjassaAdsBundle:InformationUser:sendEmailSuccess.html.twig');

        } elseif ('GET' === $request->getMethod()) {

            return array(
                'form' => $form->createView(),
            );
            
        } else {
            throw new Exception("An error occurs during contact sending email");
        }
    }
}