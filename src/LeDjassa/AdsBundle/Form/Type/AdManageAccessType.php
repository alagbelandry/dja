<?php

namespace LeDjassa\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class for manage action on ad
 *
 * @author Landry Alagbe
 */
class AdManageAccessType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder->add('user_password', 'password', array(
            'label' => 'Mot de passe :',
            'attr'  => array(
                'autocomplete' => 'off'
        )));
    }

    public function getName()
    {
        return 'ad_manage';
    }

}