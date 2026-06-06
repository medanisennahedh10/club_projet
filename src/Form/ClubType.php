<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'];

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du club',
                'attr'  => ['placeholder' => 'Ex: Club Robotique'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr'  => [
                    'placeholder' => 'Décrivez le club...',
                    'rows'        => 4,
                ],
            ])
            ->add('logo_file', FileType::class, [
                'label'    => 'Logo du club',
                'mapped'   => false,
                'required' => !$isEdit,
                'constraints' => [
                    new File([
                        'maxSize'          => '2M',
                        'mimeTypes'        => ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'],
                        'mimeTypesMessage' => 'Formats acceptés : JPG, PNG, WEBP, SVG.',
                    ]),
                ],
            ])
            ->add('proposed_by_id', EntityType::class, [
                'label'        => 'Proposé par',
                'class'        => User::class,
                'choice_label' => fn(User $u) => $u->getFirstname() . ' ' . $u->getLastname() . ' (' . $u->getEmail() . ')',
                'placeholder'  => '— Choisir un utilisateur —',
                'required'     => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
            'is_edit'    => false,
        ]);
    }
}