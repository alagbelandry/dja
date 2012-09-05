<?php

namespace LeDjassa\AdsBundle\LeDjassaAdsBundle\Form\Type\AdType

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

        // Category relation 
  
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LeDjassa\AdsBundle\LeDjassaAdsBundle\Model\Ad',
        ));
    }

    public function getName()
    {
        return 'ad';
    }
} 