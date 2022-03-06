<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->add('username')
            ->add('name')
            ->add('secondName')
            ->add('email')
            ->add('birthDate',DateType::class,[
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'html5'=>false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('Update', SubmitType::class,['attr'=>['class'=>'cmn-btn']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
