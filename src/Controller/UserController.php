<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        SluggerInterface $slugger
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Hash password
            $plain = $form->get('plainPassword')->getData();
            $user->setPassword($hasher->hashPassword($user, $plain));

            // Upload photo
            $file = $form->get('profile_picture_file')->getData();
            if ($file) {
                $filename = $slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '-' . uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('profile_pictures_directory'), $filename);
                $user->setProfilePicture($filename);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès !');
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/new.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        User $user,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        SluggerInterface $slugger
    ): Response {
        $form = $this->createForm(UserType::class, $user, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Hash password seulement si rempli
            $plain = $form->get('plainPassword')->getData();
            if ($plain) {
                $user->setPassword($hasher->hashPassword($user, $plain));
            }

            // Upload nouvelle photo
            $file = $form->get('profile_picture_file')->getData();
            if ($file) {
                $filename = $slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '-' . uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('profile_pictures_directory'), $filename);
                $user->setProfilePicture($filename);
            }

            $em->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès !');
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

   #[Route('/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
public function delete(
    Request $request,
    User $user,
    EntityManagerInterface $em
): Response {
    if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
        
        // ✅ 1. Dissocier les clubs proposés par ce user
        foreach ($user->getClubs() as $club) {
            $club->setProposedById(null);
        }

        // ✅ 2. Supprimer les reclamations
        foreach ($user->getReclamations() as $reclamation) {
            $em->remove($reclamation);
        }

        // ✅ 3. Supprimer les club members
        foreach ($user->getClubMembers() as $clubMember) {
            $em->remove($clubMember);
        }

        // ✅ 4. Supprimer les candidatures
        foreach ($user->getCandidatures() as $candidature) {
            $em->remove($candidature);
        }

        // ✅ 5. Supprimer les participations
        foreach ($user->getParticipations() as $participation) {
            $em->remove($participation);
        }

        // ✅ 6. Supprimer les feedbacks
        foreach ($user->getFeedback() as $feedback) {
            $em->remove($feedback);
        }

        $em->flush(); // flush avant pour appliquer les dissociations

        // ✅ 7. Maintenant supprimer le user
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès.');
    }

    return $this->redirectToRoute('app_user_index');
}
}