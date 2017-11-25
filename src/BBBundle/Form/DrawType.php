<?php

namespace BBBundle\Form;

use function PHPSTORM_META\type;
use function Sodium\add;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DrawType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title',TextType::class,array('label'=>'Nom du dessin','attr'=>array('class'=>'form-control')))
            //->add('dPath')
            ->add('description',TextareaType::class,array('label'=>'Description','attr'=>array('class'=>'form-control','type'=>'textarea')))
            //->add('createdAt' )
            //->add('updatedAt')
            ->add('picture', FileType::class)
            ->add('category', EntityType::class, array('class'=>'BBBundle\Entity\Category','choice_label'=>'name','label'=>'Catégorie', 'attr'=>array('class'=>'form-control')))//Entitytype
            ->add('valider', SubmitType::class, array('attr'=>array('class'=>'btn btn-default navbar-right')));
    }

    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BBBundle\Entity\Draw'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bbbundle_draw';
    }


}
