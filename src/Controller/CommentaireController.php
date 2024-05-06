<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Post;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



use App\Service\SmsGenerator;

use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

   

   

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Tout d'abord, persistez l'entité Commentaire
            $entityManager->persist($commentaire);
            $entityManager->flush();
    
            // Ensuite, mettez à jour le nombre de commentaires dans l'entité Post
            $post = $commentaire->getPost();
            $post->setNbComments($post->getNbComments() + 1);
            $entityManager->persist($post);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_commentaire_index');
        }
    
        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }
    
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    
    #[Route('/{id_post}/newFront', name: 'app_commentaire_newFront', methods: ['GET', 'POST'])]
    public function newFront(Request $request, EntityManagerInterface $entityManager,Post $post,UtilisateurRepository $userRepository,SmsGenerator $smsGenerator): Response
    {
         // Créez une instance du PostController pour accéder à la propriété $idUtilisateurConnecte
        

         // Accédez à la propriété $idUtilisateurConnecte
        $idUtilisateurConnecte = $this->session->get('id_utilisateur'); // Retrieve idUtilisateurConnecte from session
        
        $user = $userRepository->find($idUtilisateurConnecte);
        
        $commentaire = new Commentaire();
        $commentaire->setUtilisateur($user);
        $commentaire->setPost($post);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {




            $badWords = ['mot1', 'mot2', 'mot3']; // Ajoutez ici vos mots interdits
            $commentContent = $commentaire->getDescriptionCo();
            foreach ($badWords as $badWord) {
                if (stripos($commentContent, $badWord) !== false) {
                    // Le commentaire contient un mot interdit
                    // Vous pouvez choisir ici comment vous souhaitez gérer ce cas
                    // Par exemple, vous pouvez ignorer le commentaire ou le signaler
                    // Ici, je vais simplement retourner un message d'erreur
                    // Afficher l'alerte et bloquer l'exécution jusqu'à ce que l'utilisateur appuie sur OK
                     
                    
                    $name=$commentaire->getUtilisateur()->getNom();

                    $text=$commentaire->getDescriptionCo();
            
                    $number_test=$_ENV['twilio_to_number'];// Numéro vérifier par twilio. Un seul numéro autorisé pour la version de test.
            
                    //Appel du service
                    $smsGenerator->sendSms($number_test ,$name,$text);


                    $this->addFlash('error', ' Warning ! Consultez votre SMS pour plus information.'); //lappel teeha fel fichier twig melekher
                

                    return $this->redirectToRoute('app_post_front');
                }
            }



            // Ensuite, mettez à jour le nombre de commentaires dans l'entité Post
            $post = $commentaire->getPost();
            $post->setNbComments($post->getNbComments() + 1);
            $entityManager->persist($post);
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/newFront.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    
    #[Route('/{id_commentaire}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id_commentaire}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            
            $entityManager->flush();
            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }
    #[Route('/{id_commentaire}/editFront', name: 'app_commentaire_editFront', methods: ['GET', 'POST'])]
    public function editFront(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager,SmsGenerator $smsGenerator): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {





            $badWords = ['mot1', 'mot2', 'mot3']; // Ajoutez ici vos mots interdits
            $commentContent = $commentaire->getDescriptionCo();
            foreach ($badWords as $badWord) {
                if (stripos($commentContent, $badWord) !== false) {
                    // Le commentaire contient un mot interdit
                    // Vous pouvez choisir ici comment vous souhaitez gérer ce cas
                    // Par exemple, vous pouvez ignorer le commentaire ou le signaler
                    // Ici, je vais simplement retourner un message d'erreur
                    // Afficher l'alerte et bloquer l'exécution jusqu'à ce que l'utilisateur appuie sur OK
                     
                    
                    $name=$commentaire->getUtilisateur()->getNom();

                    $text=$commentaire->getDescriptionCo();
            
                    $number_test=$_ENV['twilio_to_number'];// Numéro vérifier par twilio. Un seul numéro autorisé pour la version de test.
            
                    //Appel du service
                    $smsGenerator->sendSms($number_test ,$name,$text);


                    $this->addFlash('error', ' Warning ! Consultez votre SMS pour plus information.'); //lappel teeha fel fichier twig melekher
                

                    return $this->redirectToRoute('app_post_front');
                }
            }







            $entityManager->flush();

            return $this->redirectToRoute('app_post_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/editFront.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id_commentaire}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getIdcommentaire(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/front/{id_commentaire}', name: 'app_commentaire_deleteFront', methods: ['POST'])]
    public function deleteFront(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getIdcommentaire(), $request->request->get('_token'))) {
            $post = $commentaire->getPost();
            $post->setNbComments($post->getNbComments() - 1);
            $entityManager->persist($post);
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_front', [], Response::HTTP_SEE_OTHER);
    }
}