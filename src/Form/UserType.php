<?php

namespace App\Form;


use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Entity\User;
use App\Enum\UserRole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'];

        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr'  => ['placeholder' => 'Prénom'],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr'  => ['placeholder' => 'Nom'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr'  => [
                    'placeholder' => 'exemple@email.com',
                    'readonly'    => $isEdit,
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label'    => $isEdit ? 'Nouveau mot de passe' : 'Mot de passe',
                'mapped'   => false,
                'required' => !$isEdit,
                'attr'     => [
                    'placeholder' => $isEdit ? 'Laisser vide pour ne pas changer' : 'Mot de passe',
                ],
                'constraints' => $isEdit ? [] : [
                    new NotBlank(['message' => 'Le mot de passe est obligatoire.']),
                ],
            ])
            // ✅ APRÈS
->add('role', EnumType::class, [
    'label' => 'Rôle',
    'class' => UserRole::class,
])
            ->add('dtype', ChoiceType::class, [
                'label'   => 'Type',
                'choices' => [
                    'Étudiant'    => 'student',
                    'Enseignant'  => 'teacher',
                    'Admin'       => 'admin',
                ],
            ])
            ->add('profile_picture_file', FileType::class, [
                'label'    => 'Photo de profil',
                'mapped'   => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize'          => '2M',
                        'mimeTypes'        => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Formats acceptés : JPG, PNG, WEBP.',
                    ]),
                ],
            ])
            ->add('is_verified', CheckboxType::class, [
                'label'    => 'Compte vérifié',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit'    => false,
        ]);
    }
}