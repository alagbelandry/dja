<?php

namespace LeDjassa\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class for manage city form
 *
 * @author Landry Alagbe
 */
class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('area', 'model', array(
            'class'         => 'LeDjassa\AdsBundle\Model\Area',
            'label'         => 'Region :',
            'empty_value'   => 'Choisir une region',
            'property_path' => false,
        ));

        $builder->add('name', 'model', array(
            'class'         => 'LeDjassa\AdsBundle\Model\City',
            'label'         => 'Ville :',
            'empty_value'   => 'Choisir une ville',
            'attr'          => array(
                'class' => 's_form_add_name'),
            'label_attr'    => array(
                'class' => 's_form_add_name')
        ));

        // City relation
        $builder->add('quarter', new QuarterType(), array(
            'property_path' => false,
            'attr'          => array(
                'class' => 's_form_add_quarter'),
            'label_attr'    => array(
                'class' => 's_form_add_quarter')
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LeDjassa\AdsBundle\Model\City',
        ));
    }

    public function getName()
    {
        return 'city';
    }

}
