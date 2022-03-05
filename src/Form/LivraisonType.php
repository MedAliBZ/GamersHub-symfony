<?php

namespace App\Form;

use App\Entity\Livraison;
use App\Entity\Livreur;
use App\Entity\MockCommande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adress')
            ->add('ville')
            ->add('code_postal')
            ->add('etat')
            ->add('livreur', EntityType::class, [
                'class' => Livreur::class,
                'choice_label' => 'nom',
            ])
            ->add('commande', EntityType::class, [
                'class' => MockCommande::class,
                'choice_label' => 'id',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
