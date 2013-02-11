<?php

namespace LeDjassa\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class for manage search ad
 *
 * @author Landry Alagbe
 */
class AdSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'search', array(
            'required'      => false,
        ));

        $builder->add('category', 'model', array(
            'class'         => 'LeDjassa\AdsBundle\Model\Category',
            'property'      => 'title',
            'required'      => false,
            'empty_value'   => 'Toutes catégories',
        ));

        $builder->add('area', 'model', array(
            'class'         => 'LeDjassa\AdsBundle\Model\Area',
            'required'      => false,
            'empty_value'   => 'Toute la Cote D\'ivoire ',
        ));
    }

    public function getName()
    {
        return 'ad_search';
    }
}
