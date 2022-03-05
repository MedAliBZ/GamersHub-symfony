<?php

namespace App\Form;

use App\Entity\Matchs;
use App\Entity\Teams;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamsBackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Team_name')
            ->add('gamersNb')
            ->add('rank')
            ->add('verified')
            ->add('matchs',EntityType::class,[
                'class'=>Matchs::class,
                'choice_label'=>'MatchName'
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teams::class,
        ]);
    }
}
