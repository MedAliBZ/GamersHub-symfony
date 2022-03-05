<?php

namespace App\Form;

use App\Entity\Matchs;
use App\Entity\Teams;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TeamsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Team_name')
            ->add('gamersNb')
            ->add('rank')
            ->add('matchs',EntityType::class,[
                'class'=>Matchs::class,
                'choice_label'=>'MatchName'
            ])
            ->add('image', FileType::Class,[
                'mapped'=> false,
                'label'=>'please upload your team picture',
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
