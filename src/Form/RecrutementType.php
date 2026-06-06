<?php

namespace App\Form;

use App\Entity\Recrutement;
use App\Entity\Club;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecrutementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description', TextareaType::class)
            ->add('requirements', TextareaType::class)
            ->add('deadline', DateTimeType::class, ['widget' => 'single_text'])
            ->add('status', ChoiceType::class, [
                'choices' => ['Ouvert' => 'Ouvert', 'Fermé' => 'Fermé'],
            ])
            ->add('club_id', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Recrutement::class]);
    }
}   