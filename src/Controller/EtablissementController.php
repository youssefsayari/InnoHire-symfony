<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Entity\Wallet;
use App\Form\EtablissementType;
use App\Repository\EtablissementRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;






use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/etablissement')]
class EtablissementController extends AbstractController
{
    #[Route('/', name: 'app_etablissement_index', methods: ['GET'])]
    public function index(EtablissementRepository $etablissementRepository): Response
    {
  
        return $this->render('etablissement/index.html.twig', [
            'etablissements' => $etablissementRepository->findAll(),
        ]);
    }


//------------------front

    #[Route('/front', name: 'app_etablissement_front', methods: ['GET'])]
    public function front(EtablissementRepository $etablissementRepository,
    WalletRepository $walletRepository): Response
    {

        $etablissements = $etablissementRepository->findAll(); // Get all establishments

        $etablissementsAndWallets = []; // Initialize empty array

        foreach ($etablissements as $etablissement) {
            $wallets = $walletRepository->findBy(['etablissement' => $etablissement]);

            $etablissementsAndWallets[] = [
                'etablissement' => $etablissement,
                'wallets' => $wallets,
            ];
        }

        return $this->render('etablissement/front.html.twig', [
            'etablissementsAndWallets' => $etablissementsAndWallets,
        ]);
    }

    #[Route('/newFront', name: 'app_etablissement_newFront', methods: ['GET', 'POST'])]
    public function newFront(Request $request, EntityManagerInterface $entityManager, EtablissementRepository $etablissementRepository): Response
    {
        $etablissement = new Etablissement();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $codeEtablissement = $etablissement->getCodeEtablissement();               
          // Vérifier si le code établissement est unique
          if (!$etablissementRepository->isCodeEtablissementUnique($codeEtablissement)) {
            $this->addFlash('error', 'Le code établissement existe déjà. Veuillez entrer un code unique.'); 
            // Retourner la vue avec les données déjà soumises
            return $this->renderForm('etablissement/newFront.html.twig', [
                'etablissement' => $etablissement,
                'form' => $form,
            ]);
        }
        


            $entityManager->persist($etablissement);
            $entityManager->flush();

            return $this->redirectToRoute('app_etablissement_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etablissement/newFront.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form,
        ]);
    }


    #[Route('/front/{id}', name: 'app_etablissement_deleteFront', methods: ['POST'])]
    public function deleteFront(Request $request, Etablissement $etablissement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etablissement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($etablissement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_etablissement_front', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/front/{id}/edit', name: 'app_etablissement_editFront', methods: ['GET', 'POST'])]
    public function editFront(Request $request, Etablissement $etablissement, EntityManagerInterface $entityManager,EtablissementRepository $etablissementRepository): Response
    {
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            
            
            $codeEtablissement = $etablissement->getCodeEtablissement();               
            // Vérifier si le code établissement est unique
            if (!$etablissementRepository->isCodeEtablissementUnique($codeEtablissement,$etablissement)) {
              $this->addFlash('error', 'Le code établissement existe déjà. Veuillez entrer un code unique.'); 
              // Retourner la vue avec les données déjà soumises
              return $this->renderForm('etablissement/editFront.html.twig', [
                  'etablissement' => $etablissement,
                  'form' => $form,
              ]);
          }
            
            
            
            $entityManager->flush();

            return $this->redirectToRoute('app_etablissement_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etablissement/editFront.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form,
        ]);
    }

    //------------------End-Front--------------------




    #[Route('/new', name: 'app_etablissement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EtablissementRepository $etablissementRepository): Response
    {
        $etablissement = new Etablissement();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $codeEtablissement = $etablissement->getCodeEtablissement();               
          // Vérifier si le code établissement est unique
          if (!$etablissementRepository->isCodeEtablissementUnique($codeEtablissement)) {
            $this->addFlash('error', 'Le code établissement existe déjà. Veuillez entrer un code unique.'); 
            // Retourner la vue avec les données déjà soumises
            return $this->renderForm('etablissement/new.html.twig', [
                'etablissement' => $etablissement,
                'form' => $form,
            ]);
        }
        


            $entityManager->persist($etablissement);
            $entityManager->flush();

            return $this->redirectToRoute('app_etablissement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etablissement/new.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_etablissement_show', methods: ['GET'])]
    public function show(Etablissement $etablissement): Response
    {
        return $this->render('etablissement/show.html.twig', [
            'etablissement' => $etablissement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etablissement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etablissement $etablissement, EntityManagerInterface $entityManager, EtablissementRepository $etablissementRepository): Response
    {
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $codeEtablissement = $etablissement->getCodeEtablissement();               
            // Vérifier si le code établissement est unique
            if (!$etablissementRepository->isCodeEtablissementUnique($codeEtablissement,$etablissement)) {
              $this->addFlash('error', 'Le code établissement existe déjà. Veuillez entrer un code unique.'); 
              // Retourner la vue avec les données déjà soumises
              return $this->renderForm('etablissement/edit.html.twig', [
                  'etablissement' => $etablissement,
                  'form' => $form,
              ]);
          }

            
            $entityManager->flush();
           return $this->redirectToRoute('app_etablissement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etablissement/edit.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etablissement_delete', methods: ['POST'])]
    public function delete(Request $request, Etablissement $etablissement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etablissement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($etablissement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_etablissement_index', [], Response::HTTP_SEE_OTHER);
    }


    


}
