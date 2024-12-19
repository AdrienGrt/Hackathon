<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Facture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('dateEnvoi', null, [
                'widget' => 'single_text',
            ])
            ->add('statut')
            ->add('montant')
            ->add('client', EntityType::class, [
                'class' => Client::class,
                // Afficher le nom du client dans le formulaire, et non son ID
                'choice_label' => 'nom', // Assurez-vous que la propriété 'nom' existe dans l'entité Client
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
