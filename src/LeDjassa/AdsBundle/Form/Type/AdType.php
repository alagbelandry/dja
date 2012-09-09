<?php

namespace LeDjassa\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use LeDjassa\AdsBundle\Model\UserTypeQuery;

/**
 * Class for manage ad form
 *
 * @author Landry Alagbe
 */
class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'Titre :',
        ));

        $builder->add('description', 'textarea', array(
            'label' => 'Description :',
        ));

        $builder->add('price', 'money', array(
            'label'    => 'Prix :',
            'currency' => 'CFA',
        ));

        // Category  relation
        $builder->add('category', 'model', array(
            'class'    => 'LeDjassa\AdsBundle\Model\Category',
            'label'    => 'Categorie :',
            'property' => 'title',
        ));

        // User type relation 
        $builder->add('user_type', 'model', array(
            'class'    => 'LeDjassa\AdsBundle\Model\UserType',
            'label'    => 'Vous publiez l\'annonce en tant que : ',
            'property' => 'title',
            'expanded' => true,
            'multiple' => false,
            'query'    => UserTypeQuery::create()->orderByTitle(UserTypeQuery::ASC),
        ));

        // Ad type relation 
  		$builder->add('ad_type', 'model', array(
            'class'    => 'LeDjassa\AdsBundle\Model\AdType',
            'label'    => 'Type d\'annonce :',
            'property' => 'name',
            'expanded' => true,
            'multiple' => false,
        ));

        // City relation
        $builder->add('city', new CityType());
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