<?php

namespace App\Form;

use App\Entity\Tournaments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TournamentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('decription')
            ->add('teamSize')
            ->add('startDate',DateType::class)
            ->add('finishDate',DateType::class)
            ->add('maxT')
            ->add('images', FileType::class,[
                'mapped'=> false,
                'label'=>'please upload pictures',
            ])
            //->add('Add', SubmitType::class,['attr'=>['class'=>'cmn-btn']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournaments::class,
        ]);
    }
}
