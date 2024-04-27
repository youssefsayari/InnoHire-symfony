<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    ///////////////////////////////////////// Front //////////////////////////////////////
    #[Route('/front', name: 'app_reclamation_indexfront', methods: ['GET'])]
    public function indexfront(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/indexfront.html.twig', [
            'reclamations' => $reclamationRepository->findByUserId(3),
        ]);
    }
    ////////////////////////////////////////////////////////////////////////////////////

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        
        // Set the default value for the date field as a DateTime object
        $reclamation->setDate(new \DateTime());
    
        // Set the id_utilisateur to 1
        $reclamation->setUtilisateur($this->getDoctrine()->getRepository(Utilisateur::class)->find(3));
        
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    /////////////////////////////////////////New Front //////////////////////////////////////    
    #[Route('/newfront', name: 'app_reclamation_newfront', methods: ['GET', 'POST'])]
    public function newfront(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        
        // Set the default value for the date field as a DateTime object
        $reclamation->setDate(new \DateTime());
    
        // Set the id_utilisateur to 1
        $reclamation->setUtilisateur($this->getDoctrine()->getRepository(Utilisateur::class)->find(3));
        
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_reclamation_indexfront', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reclamation/newfront.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
        ////////////////////////////////////////////////////////////////////////////////////

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
//////////////////////////////////////        Edit Front       //////////////////////////////////////////////

#[Route('/{id}/editfront', name: 'app_reclamation_editfront', methods: ['GET', 'POST'])]
public function editfront(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_reclamation_indexfront', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reclamation/editfront.html.twig', [
        'reclamation' => $reclamation,
        'form' => $form,
    ]);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    ////////////////////////////////////////   FRONT   /////////////////////////////////////////////////////////////////////////////////////////
    #[Route('/{id}/delete', name: 'app_reclamation_deletefront', methods: ['POST'])]
    public function deletefront(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('deletefront' . $reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_indexfront', [], Response::HTTP_SEE_OTHER);
    }
    
}