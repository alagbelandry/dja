<?php

namespace LeDjassa\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class for manage contact email
 *
 * @author Landry Alagbe
 */
class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('subject', 'text', array(
            'label' => 'Sujet :',
        ));

    	$builder->add('name', 'text', array(
            'label' => 'Votre nom :',
        ));

        $builder->add('email', 'email', array(
            'label' => 'Votre adresse email :',
        ));

        $builder->add('message', 'textarea', array(
            'label' => 'Message :',
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LeDjassa\AdsBundle\Model\InformationUser',
        ));
    }

    public function getName()
    {
        return 'contact';
    }
}