<?php

namespace App\Controller;


use App\Entity\Wallet;
use App\Form\WalletType;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\Etablissement;




use Symfony\Component\HttpFoundation\Session\SessionInterface;





/*start stripe*/
use Stripe\Checkout\Session;
use Stripe\Product;
use Stripe\Price;
/* end stripe*/

#[Route('/wallet')]
class WalletController extends AbstractController
{
    #[Route('/', name: 'app_wallet_index', methods: ['GET'])]
    public function index(WalletRepository $walletRepository): Response
    {
        return $this->render('wallet/index.html.twig', [
            'wallets' => $walletRepository->findAll(),
        ]);
    }





    private $session;
    public $idUtilisateurConnecte;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->idUtilisateurConnecte = $this->session->get('id_utilisateur');
    }


   
    /*start stripe */
    
    #[Route('/recharger/{id}', name: 'app_wallet_recharger', methods: ['GET'])]
    public function recharger(Wallet $wallet): Response
    { 
        return $this->render('wallet/recharger.html.twig', [
            'walletId' => $wallet->getId(),
        ]);
    }
    
    #[Route('/rechargerSuccess/{id}', name: 'app_wallet_rechargerSuccess', methods: ['GET'])]
    public function rechargerSuccess(Request $request,EntityManagerInterface $entityManager, WalletRepository $walletRepository,Wallet $wallet): Response
    {
        $montant = $request->query->get('montant');
        
        $balance = $wallet->getBalance();
        $newBalance = $balance + $montant;
        $wallet->setBalance($newBalance);
        $entityManager->persist($wallet);
        $entityManager->flush();

        return $this->render('wallet/rechargerSuccess.html.twig', [
            'montant' => $montant,
        ]);
    }
    #[Route('/rechargerCancel', name: 'app_wallet_rechargerCancel', methods: ['GET'])]
    public function rechargerCancel(): Response
    {
        return $this->render('wallet/rechargerCancel.html.twig'); 
    }
    
    /*end stripe */
    #[Route('/new', name: 'app_wallet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, WalletRepository $walletRepository): Response
    {
        $wallet = new Wallet();
        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // Vérifier si l'établissement existe déjà
    $idEtablissement = $wallet->getEtablissement()->getId();
    $etablissementExists = $walletRepository->etablissementExists($idEtablissement);
    if ($etablissementExists) {
        // Afficher une alerte JavaScript si l'établissement existe déjà
        echo "<script>alert('L\'établissement choisi possède déjà un Wallet');</script>";
        // Rediriger vers la page 'app_wallet_new' en JavaScript après que l'utilisateur clique sur 'OK'
        echo "<script>window.location.href = window.location.href;</script>";
        return null;

    }
    else{
        // Ajouter le wallet à la base de données
        $entityManager->persist($wallet);
        $entityManager->flush();
        return $this->redirectToRoute('app_wallet_index', [], Response::HTTP_SEE_OTHER);
    }
        }
        

        return $this->render('wallet/new.html.twig', [
            'wallet' => $wallet,
            'form' => $form->createView(),
        ]);
    }
//---------------Start Front---------------
#[Route('/{id}/newFront', name: 'app_wallet_newFront', methods: ['GET', 'POST'])]
    public function newFront(Request $request, EntityManagerInterface $entityManager, WalletRepository $walletRepository,Etablissement $etablissement): Response
    {
        $wallet = new Wallet();
        $wallet->setEtablissement($etablissement);
        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // Vérifier si l'établissement existe déjà
    $idEtablissement = $wallet->getEtablissement()->getId();
    $etablissementExists = $walletRepository->etablissementExists($idEtablissement);
    if ($etablissementExists) {
        // Afficher une alerte JavaScript si l'établissement existe déjà
        echo "<script>alert('L\'établissement choisi possède déjà un Wallet');</script>";
        // Rediriger vers la page 'app_wallet_new' en JavaScript après que l'utilisateur clique sur 'OK'
        echo "<script>window.location.href = window.location.href;</script>";
        return null;

    }
    else{
        // Ajouter le wallet à la base de données
        $entityManager->persist($wallet);
        $entityManager->flush();
        return $this->redirectToRoute('app_etablissement_front', [], Response::HTTP_SEE_OTHER);
    }
        }
        

        return $this->render('wallet/newFront.html.twig', [
            'wallet' => $wallet,
            'form' => $form->createView(),
            
        ]);
    }
//---------------End Front----------------
    #[Route('/{id}', name: 'app_wallet_show', methods: ['GET'])]
    public function show(Wallet $wallet): Response
    {
        return $this->render('wallet/show.html.twig', [
            'wallet' => $wallet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_wallet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Wallet $wallet, EntityManagerInterface $entityManager,WalletRepository $walletRepository): Response
    {

        $form = $this->createForm(WalletType::class, $wallet);
        $idEtablissementOld = $wallet->getEtablissement()->getId();

        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si l'établissement a été modifié
            $idEtablissement = $wallet->getEtablissement()->getId();
            if ($idEtablissement == $idEtablissementOld) {
                // Aucune modification de l'établissement, donc directement enregistrer et rediriger
                $entityManager->flush();
                return $this->redirectToRoute('app_wallet_index', [], Response::HTTP_SEE_OTHER);
            } else {
                // Vérifier si l'établissement existe déjà
                $etablissementExists = $walletRepository->etablissementExists($idEtablissement);
                if ($etablissementExists) {
                    // Afficher une alerte JavaScript si l'établissement existe déjà
                    echo "<script>alert('L\'établissement choisi possède déjà un Wallet');</script>";
                    // Rediriger vers la page 'app_wallet_new' en JavaScript après que l'utilisateur clique sur 'OK'
                    echo "<script>window.location.href = window.location.href;</script>";
                    return null;
                } else {
                    // Enregistrer les modifications et rediriger
                    $entityManager->flush();
                    return $this->redirectToRoute('app_wallet_index', [], Response::HTTP_SEE_OTHER);
                }
            }
        }
    
        return $this->renderForm('wallet/edit.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_wallet_delete', methods: ['POST'])]
    public function delete(Request $request, Wallet $wallet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wallet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($wallet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_wallet_index', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/{id}/acheterQuiz', name: 'acheterQuiz', methods: ['GET', 'POST'])]
    public function acheterQuiz(Request $request, Wallet $wallet, EntityManagerInterface $entityManager,WalletRepository $walletRepository,SessionInterface $session): Response
    {
        $session->set('id_wallet', $wallet->getId());

        return $this->redirectToRoute('quiz_dispo', [], Response::HTTP_SEE_OTHER);
    }




   

}
