<?php

namespace BBBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('adOne', TextType::class,array('label'=>'Premier pass','attr'=>array('class'=>'form-control', "name"=>"_adOne","id"=>"adOne")))
            ->add('adTwo', PasswordType::class, array('label'=>'Deuxième pass', 'attr'=>array('class'=>'form-control', "name"=>"_adTwo", "id"=>"adTwo")));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BBBundle\Entity\Ad'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bbbundle_ad';
    }


}
