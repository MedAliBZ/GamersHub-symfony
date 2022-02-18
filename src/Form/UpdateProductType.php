<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class UpdateProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameProduct')
            ->add('price')
            ->add('quantityStocked')
            ->add('image',FileType::class,[
                'mapped'=> false,
                'label'=>'please upload pictures',
                'multiple'=>true   
            ],array('data_class' => null),) 
            ->add('creationDate')
            ->add('modificationDate')
            ->add('isEnabled')
            ->add('category')
            ->add('update',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}

