<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Club;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_evenement')
            ->add('lieu')
            ->add('date_debut', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('date_fin', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('description')
            ->add('image')
            ->add('status', ChoiceType::class, [
                'choices' => ['À venir' => 'À venir', 'En cours' => 'En cours', 'Terminé' => 'Terminé'],
            ])
            ->add('club_id', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Evenement::class]);
    }
}