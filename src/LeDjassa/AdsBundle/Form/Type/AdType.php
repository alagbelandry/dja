<?php

namespace LeDjassa\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class for manage ad form
 *
 * @author     Landry Alagbe
 */
class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('description');
        $builder->add('price');

        // Ad type relation
        $builder->add('ad_type', 'model', array(
            'class' => 'LeDjassa\AdsBundle\Model\AdType',
        ));

        // Category relation 
  		$builder->add('category', 'model', array(
            'class' => 'LeDjassa\AdsBundle\Model\Category',
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LeDjassa\AdsBundle\Model\Ad',
        ));
    }

    public function getName()
    {
        return 'ad';
    }
} 