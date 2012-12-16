<?php

namespace LeDjassa\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class for manage send email to user who add ad
 *
 * @author Landry Alagbe
 */
class AdUserSendEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'label' => 'Votre nom :',
        ));

        $builder->add('email', 'email', array(
            'label' => 'Votre adresse email :',
        ));

        $builder->add('phone', 'number', array(
            'label'    => 'Téléphone :',
            'required' => false
        ));

        $builder->add('message', 'textarea', array(
            'label' => 'Message :',
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LeDjassa\AdsBundle\Model\InterestedUser',
        ));
    }

    public function getName()
    {
        return 'ad_user_send_email';
    }
}
