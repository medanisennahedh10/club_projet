<?php

namespace App\Controller;

use App\Entity\ClubMember;
use App\Form\ClubMemberType;
use App\Repository\ClubMemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/clubmember')]
class ClubMemberController extends AbstractController
{
    #[Route('/', name: 'app_club_member_index', methods: ['GET'])]
    public function index(ClubMemberRepository $clubMemberRepository): Response
    {
        return $this->render('club_member/index.html.twig', [
            'club_members' => $clubMemberRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_club_member_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $clubMember = new ClubMember();
        $clubMember->setJoinedAt(new \DateTimeImmutable()); // تعيين تاريخ الانضمام تلقائياً

        $form = $this->createForm(ClubMemberType::class, $clubMember);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($clubMember);
            $entityManager->flush();

            $this->addFlash('success', 'Membre ajouté au club avec succès !');
            return $this->redirectToRoute('app_club_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('club_member/new.html.twig', [
            'club_member' => $clubMember,
            'form' => $form->createView(),
        ]);
    }

   #[Route('/{id}/edit', name: 'app_club_member_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ClubMember $clubMember, EntityManagerInterface $entityManager): Response
    {
        // 1. نقوم بحفظ النسخة الأصلية من البيانات قبل معالجة الفورم لضمان عدم ضياعها
        $originalUser = $clubMember->getUserId();
        $originalClub = $clubMember->getClubId();

        $form = $this->createForm(ClubMemberType::class, $clubMember);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 2. فرض إعادة البيانات الأصلية لمنع الـ null أو التلاعب
            $clubMember->setUserId($originalUser);
            $clubMember->setClubId($originalClub);

            $entityManager->flush();

            $this->addFlash('success', 'Rôle du membre modifié avec succès !');
            return $this->redirectToRoute('app_club_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('club_member/edit.html.twig', [
            'club_member' => $clubMember,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_club_member_delete', methods: ['POST'])]
    public function delete(Request $request, ClubMember $clubMember, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clubMember->getId(), $request->request->get('_token'))) {
            $entityManager->remove($clubMember);
            $entityManager->flush();

            $this->addFlash('success', 'Membre retiré du club !');
        }

        return $this->redirectToRoute('app_club_member_index', [], Response::HTTP_SEE_OTHER);
    }
}