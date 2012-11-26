<?php

namespace LeDjassa\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class for manage edit ad form
 *
 * @author Landry Alagbe
 */
class AdEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description', 'textarea', array(
            'label' => 'Description :',
        ));

        $builder->add('price', 'money', array(
            'label'     => 'Prix :',
            'currency'  => 'CFA',
            'precision' => 0,
            'required'  => false, 
        ));

        // Picture ad relation 
        $builder->add('picture_ads', 'collection', array(
            'type'         => new PictureAdType(),
            'prototype'    => true,
            'allow_add'    => true,
            'allow_delete' => true,
            'required'     => false,
            'by_reference' => false,
            'label'        => 'Photos d\'illustration',
        ));

        $builder->add('user_password', 'password', array(
            'label'         => 'Mot de passe :',
            'property_path' => false,
            'attr'          => array(
                'autocomplete' => 'off'
        )));
    }
   
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LeDjassa\AdsBundle\Model\Ad',
        ));
    }

    public function getName()
    {
        return 'ad_edit';
    }
} 