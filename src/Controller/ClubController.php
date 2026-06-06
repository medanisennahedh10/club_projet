<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/club')]
class ClubController extends AbstractController
{
    #[Route('/', name: 'app_club_index', methods: ['GET'])]
    public function index(ClubRepository $clubRepository): Response
    {
        return $this->render('club/index.html.twig', [
            'clubs' => $clubRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_club_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Upload logo
            $file = $form->get('logo_file')->getData();
            if ($file) {
                $filename = $slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '-' . uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('club_logos_directory'), $filename);
                $club->setLogo($filename);
            }

            $em->persist($club);
            $em->flush();

            $this->addFlash('success', 'Club créé avec succès !');
            return $this->redirectToRoute('app_club_index');
        }

        return $this->render('club/new.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/edit', name: 'app_club_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Club $club,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $form = $this->createForm(ClubType::class, $club, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Upload nouveau logo
            $file = $form->get('logo_file')->getData();
            if ($file) {
                $filename = $slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '-' . uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('club_logos_directory'), $filename);
                $club->setLogo($filename);
            }

            $em->flush();

            $this->addFlash('success', 'Club modifié avec succès !');
            return $this->redirectToRoute('app_club_index');
        }

        return $this->render('club/edit.html.twig', [
            'form' => $form,
            'club' => $club,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_club_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Club $club,
        EntityManagerInterface $em
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $club->getId(), $request->request->get('_token'))) {

            // Dissocier les membres avant suppression
            foreach ($club->getClubMembers() as $member) {
                $em->remove($member);
            }

            $em->flush();
            $em->remove($club);
            $em->flush();

            $this->addFlash('success', 'Club supprimé avec succès.');
        }

        return $this->redirectToRoute('app_club_index');
    }
}