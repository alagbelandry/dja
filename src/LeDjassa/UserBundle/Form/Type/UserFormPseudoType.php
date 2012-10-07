<?php 
namespace LeDjassa\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class for manage ad form
 *
 * @author Landry Alagbe
 */
class UserFormPseudoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', array(
            'label' => 'Nom :',
        ));

        $builder->add('email', 'email', array(
            'label' => 'Adresse email :',
        ));

        $builder->add('phone', 'number', array(
            'label' => 'Téléphone :',
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FOS\UserBundle\Propel\User',
        ));
    }

    public function getName()
    {
        return 'user_pseudo';
    }
}