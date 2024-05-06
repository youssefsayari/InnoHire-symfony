<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\CommentaireRepository;
use App\Repository\PostRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\UtilisateurLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }


    private $session;
    public $idUtilisateurConnecte;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->idUtilisateurConnecte = $this->session->get('id_utilisateur');
    }


    #[Route('/front', name: 'app_post_front', methods: ['GET'])]
    public function front(PostRepository $postRepository, CommentaireRepository $commentaireRepository, UtilisateurLikeRepository $utilisateurLikeRepository): Response
    {
        $idUtilisateurConnecte = $this->idUtilisateurConnecte; // Supposons que l'ID de l'utilisateur connecté est récupéré à partir du système de sécurité Symfony

        $posts = $postRepository->findAll(); // Récupérer tous les posts
        $postsAndComments = []; // Initialiser un tableau vide pour stocker les posts et les commentaires associés
        foreach ($posts as $post) {
            $comments = $commentaireRepository->findBy(['post' => $post]); // Récupérer les commentaires pour chaque post

            // Vérifier si le post est aimé par l'utilisateur connecté
            $isLikedByUser = $utilisateurLikeRepository->isPostLikedByUser($post->getIdPost(), $idUtilisateurConnecte);

            // Ajouter le post, les commentaires et l'état de like à un tableau
            $postsAndComments[] = [
                'post' => $post,
                'comments' => $comments,
                'isLikedByUser' => $isLikedByUser,
            ];
        }

        // Rendre le template Twig avec les données nécessaires
        return $this->render('post/front.html.twig', [
            'postsAndComments' => $postsAndComments,
            'idUtilisateurConnecte' => $idUtilisateurConnecte,
        ]);
    }
    #[Route('/{id_post}/postLike', name: 'app_post_like', methods: ['GET'])]
    public function postLike(EntityManagerInterface $entityManager,UtilisateurLikeRepository $UtilisateurLikeRepository , Post $post): Response
    {
        $id_utilisateur = $this->idUtilisateurConnecte;
        $id_post = $post->getIdPost();
        $UtilisateurLikeRepository->addUserLike($id_post, $id_utilisateur);



        
        $post->setTotalReactions($post->getTotalReactions() + 1);
        $entityManager->persist($post);
        $entityManager->flush();



        return $this->redirectToRoute('app_post_front');
    }
    #[Route('/{id_post}/postDislike', name: 'app_post_dislike', methods: ['GET'])]
    public function postDislike(EntityManagerInterface $entityManager,UtilisateurLikeRepository $UtilisateurLikeRepository , Post $post): Response
    {
        $id_utilisateur = $this->idUtilisateurConnecte;
        $id_post = $post->getIdPost();
        $UtilisateurLikeRepository->removeUserLike($id_post, $id_utilisateur);




        $post->setTotalReactions($post->getTotalReactions() - 1);
        $entityManager->persist($post);
        $entityManager->flush();



        return $this->redirectToRoute('app_post_front');
    }
    
    
    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }
     //#FRONT AJOUTER#}
    #[Route('/newFront', name: 'app_post_newFront', methods: ['GET', 'POST'])]
    public function newFront(Request $request, EntityManagerInterface $entityManager,UtilisateurRepository $userRepository): Response
    {
        $idUtilisateurConnecte = $this->idUtilisateurConnecte;
        $user = $userRepository->find($idUtilisateurConnecte);
        
        $post = new Post();
        $post->setUtilisateur($user);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/contact.html.twig', [
            'post' => $post,
            'form' => $form,
            
        ]);
    }

    #[Route('/{id_post}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/details.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id_post}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }
    #[Route('/{id_post}/editfront', name: 'app_post_editfront', methods: ['GET', 'POST'])]
    public function editfront(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/editfront.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id_post}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getIdpost(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }

    
    
    #[Route('/front/{id_post}', name: 'app_post_deleteFront', methods: ['POST'])]
    public function deleteFront(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getIdpost(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_front', [], Response::HTTP_SEE_OTHER);
    }
}