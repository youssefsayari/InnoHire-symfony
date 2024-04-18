<?php

namespace App\Controller;

use Exception;
use App\Form\AdminType;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(Request $request, UtilisateurRepository $userRepository): Response
    {
        // Check if the request is a POST to handle form submission
        if ($request->isMethod('POST')) {
            $cin = $request->request->get('cin');
            $mdp = $request->request->get('password');
    
            if (empty($cin)) {
                $error = 'CIN is required.';
                return $this->render('utilisateur/login.html.twig', ['error' => $error]);
            }
    
            // Find the user by credentials using the repository method
            $user = $userRepository->findUserByCredentials($cin, $mdp);
    
            if ($user) {
                // Redirect to the index page if user is found
                return $this->redirectToRoute('app_utilisateur_index');
            } else {
                // Show error message if credentials are invalid
                $error = 'Invalid credentials. Please try again.';
                return $this->render('utilisateur/login.html.twig', ['error' => $error]);
            }
        }
    
        // If it's a GET request, simply show the login form
        return $this->render('utilisateur/login.html.twig');
    }
    #[Route('/register', name: 'app_utilisateur_register', methods: ['GET', 'POST'])]
    public function register(Request $request): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($utilisateur);
            $entityManager->flush();
    
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('utilisateur/register.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }
 

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(AdminType::class, $utilisateur);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form['image']->getData();
            
            if ($imageFile) {
                // Use the original filename of the uploaded file
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Use the file extension of the uploaded file
                $fileExtension = $imageFile->guessExtension();
    
                // Concatenate the original filename and extension
                $imageName = $originalFilename.'.'.$fileExtension;
    
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'), // Defined in services.yaml
                        $imageName
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // You may want to customize this part based on your application's needs
                    throw new Exception('Unable to upload the image file.');
                }
    
                // Store the image file name in the database
                $utilisateur->setImage($imageName);
            }
    
            // Persist the Utilisateur entity
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

    $form = $this->createForm(AdminType::class, $utilisateur);
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
