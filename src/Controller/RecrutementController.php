<?php

namespace App\Controller;

use App\Entity\Recrutement;
use App\Form\RecrutementType;
use App\Repository\RecrutementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recrutement')]
class RecrutementController extends AbstractController
{
    #[Route('/', name: 'app_recrutement_index', methods: ['GET'])]
    public function index(RecrutementRepository $repo): Response
    {
        return $this->render('recrutement/index.html.twig', ['recrutements' => $repo->findAll()]);
    }

    #[Route('/new', name: 'app_recrutement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $r = new Recrutement();
        $r->setCreatedAt(new \DateTimeImmutable());
        $form = $this->createForm(RecrutementType::class, $r);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($r);
            $em->flush();
            return $this->redirectToRoute('app_recrutement_index');
        }
        return $this->render('recrutement/new.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/edit', name: 'app_recrutement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recrutement $r, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecrutementType::class, $r);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_recrutement_index');
        }
        return $this->render('recrutement/edit.html.twig', ['form' => $form, 'recrutement' => $r]);
    }

    #[Route('/{id}/delete', name: 'app_recrutement_delete', methods: ['POST'])]
    public function delete(Request $request, Recrutement $r, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$r->getId(), $request->request->get('_token'))) {
            $em->remove($r);
            $em->flush();
        }
        return $this->redirectToRoute('app_recrutement_index');
    }
}