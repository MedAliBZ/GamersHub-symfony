<?php

namespace App\Form;

use App\Entity\Sessioncoaching;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessioncoachingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix')
            ->add('description')
            ->add('date_debut',DateType::class,[
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'html5'=>false,
                'placeholder' => 'Select a value',
                'attr' => ['class' => 'js-datepicker'],])
            ->add('date_fin',DateType::class,[
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'html5'=>false,
                'placeholder' => 'Select a value',
                'attr' => ['class' => 'js-datepicker'],])
            ->add('background_color', ColorType::class)
            ->add('border_color',ColorType::class)
            ->add('user', EntityType::class,[
                'class'=>User::class,
                'choice_label'=>'username',
                'multiple'=>false,
                'expanded'=>false,

            ])
            ->add('Submit', SubmitType::class, ['attr'=>['class'=>'cmn-btn']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sessioncoaching::class,
        ]);
    }
}
