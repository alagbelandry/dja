<?php

namespace LeDjassa\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class for manage action password forgot
 *
 * @author Landry Alagbe
 */
class PasswordForgotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user_email', 'email', array(
            'label' => 'E-mail :',
        ));

    }

    public function getName()
    {
        return 'password_forgot';
    }

}
