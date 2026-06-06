<?php

namespace App\Form;

use App\Entity\ClubMember;
use App\Entity\User;
use App\Entity\Club;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClubMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_id', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email', // أو أي حقل آخر يمثل اسم المستخدم مثل 'nom' أو 'username'
                'label' => 'Membre (Utilisateur)',
                'attr' => ['class' => 'w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm']
            ])
           ->add('club_id', EntityType::class, [
    'class' => Club::class,
    'choice_label' => 'name', // التعديل هنا: استخدام name لكي تظهر الأسماء في القائمة المنسدلة
    'label' => 'Club',
    'attr' => ['class' => 'w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm']
])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle dans le Club',
                'choices' => [
                    'Membre' => 'Membre',
                    'Responsable' => 'Responsable',
                    'Président' => 'Président',
                    'Trésorier' => 'Trésorier',
                    'Secrétaire Général' => 'Secrétaire Général',
                ],
                'attr' => ['class' => 'w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClubMember::class,
        ]);
    }
}