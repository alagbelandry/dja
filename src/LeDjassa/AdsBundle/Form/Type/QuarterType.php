<?php

namespace LeDjassa\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class for manage quarter form
 *
 * @author Landry Alagbe
 */
class QuarterType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        
    	$builder->add('name', 'text', array(
            'label' => 'Quartier :',
            'required' => false,
            'attr'          => array(
                'class' => 's_form_add_quarter'),
            'label_attr'    => array(
                'class' => 's_form_add_quarter')
        ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LeDjassa\AdsBundle\Model\Quarter',
        ));
    }

    public function getName()
    {
        return 'quarter';
    }

}