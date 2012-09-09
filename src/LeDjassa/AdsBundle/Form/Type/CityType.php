<?php

namespace LeDjassa\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use LeDjassa\AdsBundle\Model\CityQuery;
use LeDjassa\AdsBundle\Model\AreaQuery;

/**
 * Class for manage city form
 *
 * @author Landry Alagbe
 */
class CityType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('area', 'model', array(
            'class'         => 'LeDjassa\AdsBundle\Model\Area',
            'label'         => 'Region :',
             'empty_value'  => 'Choisir une region'
          //  'query' => CityQuery::create(),
        ));

        $builder->add('name', 'model', array(
            'class'       => 'LeDjassa\AdsBundle\Model\City',
            'label'       => 'Ville :',
            'empty_value' => 'Choisir une ville'
          //  'query' => CityQuery::create(),
        ));

        // City relation
        $builder->add('quarter', new QuarterType());
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