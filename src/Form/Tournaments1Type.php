<?php

namespace App\Form;

use App\Entity\Tournaments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Tournaments1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('decription')
            ->add('teamSize')
            ->add('startDate')
            ->add('finishDate')
            ->add('maxT')
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournaments::class,
        ]);
    }
}
