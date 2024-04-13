<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{   private $utilisateurRepository;

    public function __construct(UtilisateurRepository $utilisateurRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
    }
    #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id_utilisateur}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(int $id_utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = $utilisateurRepository->find($id_utilisateur);
    
        if (!$utilisateur) {
            throw $this->createNotFoundException('Utilisateur not found');
        }
    
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }
    

    #[Route('/{id_utilisateur}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
public function edit(Request $request,int $id_utilisateur, Utilisateur $utilisateur = null, EntityManagerInterface $entityManager,UtilisateurRepository $utilisateurRepository): Response
{                 $utilisateur = $utilisateurRepository->find($id_utilisateur);
    if (!$utilisateur) {
        throw $this->createNotFoundException('Utilisateur not found');
    }

    $form = $this->createForm(UtilisateurType::class, $utilisateur);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('utilisateur/edit.html.twig', [
        'utilisateur' => $utilisateur,
        'form' => $form,
    ]);
}

#[Route('/{id_utilisateur}', name: 'app_utilisateur_delete', methods: ['POST'])]
public function delete(Request $request, int $id_utilisateur, EntityManagerInterface $entityManager, UtilisateurRepository $utilisateurRepository): Response
{
    $utilisateur = $utilisateurRepository->find($id_utilisateur);

    if (!$utilisateur) {
        throw $this->createNotFoundException('Utilisateur not found');
    }

    if ($this->isCsrfTokenValid('delete'.$utilisateur->getIdUtilisateur(), $request->request->get('_token'))) {
        $entityManager->remove($utilisateur);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
}

}
