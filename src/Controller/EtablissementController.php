<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Entity\Utilisateur;
use App\Entity\Wallet;
use App\Form\EtablissementType;
use App\Repository\EtablissementRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\Session\SessionInterface;



use TCPDF;



use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Component\HttpFoundation\RedirectResponse;



use App\Entity\GeoCalculator; // Assurez-vous que le chemin d'accès est correct


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

    #[Route('/generateEtab_pdf', name: 'generateEtab_pdf')]
    
        public function generatePdf(): Response
        {
            // Créer une instance de TCPDF
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
            // Set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Your Name');
            $pdf->SetTitle('Your Title');
            $pdf->SetSubject('Your Subject');
            $pdf->SetKeywords('Your Keywords');
    
            // Supprimer les marges
            $pdf->SetMargins(0, 0, 0);
    
            // Ajouter une nouvelle page
            $pdf->AddPage();
    
            // Obtenir le contenu de la table depuis le template Twig
            $content = $this->renderView('etablissement/pdf.html.twig', [
                'etablissements' => $this->getDoctrine()->getRepository(Etablissement::class)->findAll(), // Récupérer tous les établissements
            ]);
    
            // Écrire le contenu de la table dans le PDF
            $pdf->writeHTML($content, true, false, true, false, '');
    
            // Renvoyer le contenu du PDF comme une réponse avec le type de contenu approprié
            return new Response($pdf->Output('etablissements.pdf', 'D'), 200, [
                'Content-Type' => 'application/pdf',
            ]);
    }
    
    #[Route('/Map', name: 'app_map', methods: ['GET'])]
    public function Map(EntityManagerInterface $entityManager): Response
    {
        $etablissements = $entityManager
            ->getRepository(Etablissement::class)
            ->findAll();
            

        return $this->render('etablissement/map.html.twig', [
            'etablissements' => $etablissements,
            
        ]);
    }


//------------------front---------------------------------

#[Route('/front/Map', name: 'app_mapFront', methods: ['GET'])]
public function MapFront(EtablissementRepository $etablissementRepository,EntityManagerInterface $entityManager): Response
{
    $etablissements = $entityManager
        ->getRepository(Etablissement::class)
        ->findAll();

        $idUtilisateurConnecte = $this->idUtilisateurConnecte;
        

    return $this->render('etablissement/mapFront.html.twig', [
        'etablissements' => $etablissements,
        'idUtilisateurConnecte' => $idUtilisateurConnecte,
        
    ]);
}


private $session;
    public $idUtilisateurConnecte;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->idUtilisateurConnecte = $this->session->get('id_utilisateur');
    }


        
    #[Route('/front', name: 'app_etablissement_front', methods: ['GET'])]
    public function front(EtablissementRepository $etablissementRepository,
    WalletRepository $walletRepository): Response
    {
        
        $idUtilisateurConnecte = $this->idUtilisateurConnecte;
        //$etablissements = $etablissementRepository->findAll(); // Get all establishments
        $etablissements = $etablissementRepository->findByUserId($idUtilisateurConnecte); 

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
            'etablissementss' => $etablissementRepository->findAll(),//pour le stats au dessous
        ]);
    }

    #[Route('/newFront', name: 'app_etablissement_newFront', methods: ['GET', 'POST'])]
    public function newFront(Request $request, EntityManagerInterface $entityManager, EtablissementRepository $etablissementRepository,UtilisateurRepository $userRepository ): Response
    {
        $idUtilisateurConnecte = $this->idUtilisateurConnecte;
        $user = $userRepository->find($idUtilisateurConnecte);


        $etablissement = new Etablissement();
        $etablissement->setUtilisateur($user);
        
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
