<?php

namespace App\Form;

use App\Entity\Rating;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q1',ChoiceType::class,[
                'label'=>'Are you satisfiyed with the coach?',
                'choices' => [
                        'Yes' => 'yes_q1',
                        'No' => 'no_q1',]

            ])
            ->add('q2',ChoiceType::class,[
                'label'=>'Are you improving?',
                'choices' => [
                    'Yes' => 'yes_q2',
                    'No' => 'no_q2',]
            ])
            ->add('q3',ChoiceType::class,[
                'label'=>'did he make you stronger?',
                'choices' => [
                    'Yes' => 'yes_q3',
                    'No' => 'no_q3',]
            ])
            ->add('q4',ChoiceType::class,[
                'label'=>'Are you happy?',
                'choices' => [
                    'Yes' => 'yes_q4',
                    'No' => 'no_q4',]
            ])
            ->add('Rate', SubmitType::class, ['attr'=>['class'=>'cmn-btn']]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
