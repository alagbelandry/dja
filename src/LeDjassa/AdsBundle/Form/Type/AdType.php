<?php

namespace LeDjassa\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use LeDjassa\AdsBundle\Model\UserTypeQuery;
use LeDjassa\AdsBundle\Model\CategoryQuery;
use LeDjassa\AdsBundle\Model\Category;
use LeDjassa\AdsBundle\Model\UserType;

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
            'required' => false,
        ));

        // User information
        $builder->add('user_name', 'text', array(
            'label' => 'Votre nom :',
        ));

        $builder->add('user_email', 'email', array(
            'label' => 'Votre adresse email :',
        ));

        $builder->add('user_phone', 'text', array(
            'label'    => 'Téléphone :',
            'required' => false
        ));

        $builder->add('user_password', 'repeated', array(
            'type'           => 'password',
            'attr'           => array(
                'autocomplete' => 'off'),
            'first_options'  => array(
                'label' => 'Mot de passe'),
            'second_options' => array(
                'label' => 'Confirmer le mot de passe'),
        ));

        // Category  relation
        $builder->add('category', 'model', array(
            'class'         => 'LeDjassa\AdsBundle\Model\Category',
            'label'         => 'Categorie :',
            'property'      => 'title',
            'group_by'      => 'categoryType.title',
            'query'         => CategoryQuery::create()->joinCategoryType('categoryType'),
            'empty_value'   => 'Choisir une catégorie',
        ));

        $builder->add('user_type', 'model', array(
            'class'    => 'LeDjassa\AdsBundle\Model\UserType',
            'label'    => 'Vous publiez l\'annonce en tant que : ',
            'property' => 'title',
            'expanded' => true,
            'multiple' => false,
            'query'    => UserTypeQuery::create()->orderByTitle(UserTypeQuery::ASC),
            'attr'     => array(
                'class' => 's_form_add_field_radio')
        ));

        // Ad type relation
          $builder->add('ad_type', 'model', array(
            'class'    => 'LeDjassa\AdsBundle\Model\AdType',
            'label'    => 'Type d\'annonce :',
            'property' => 'name',
            'expanded' => true,
            'multiple' => false,
            'attr'     => array(
                'class' => 's_form_add_field_radio'),
        ));

        // City relation
        $builder->add('city', new CityType());

        // Picture ad relation
        $builder->add('picture_ads', 'collection', array(
            'type'         => new PictureAdType(),
            'prototype'    => true,
            'allow_add'    => true,
            'allow_delete' => true,
            'required'     => false,
            'by_reference' => false,
            'label'        => 'Photos d\'illustration (3 photos maximum).',
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
