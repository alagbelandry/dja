<?php

namespace LeDjassa\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class for manage action delete on ad
 *
 * @author Landry Alagbe
 */
class AdDeleteType extends AbstractType
{
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
        return 'ad_delete';
    }

}
