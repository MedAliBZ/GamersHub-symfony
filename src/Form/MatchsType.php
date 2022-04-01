<?php

namespace App\Form;

use App\Entity\Matchs;

use App\Entity\Teams;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MatchsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('MatchName')
            ->add('match_date', DateType::class)
            ->add('result')
            ->add('teams',EntityType::class,[
                'class'=>Teams::class,
                'choice_label'=>'TeamName',
                'expanded'=>false,
        'multiple'=>true,
            ])

            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matchs::class,
        ]);
    }
}
