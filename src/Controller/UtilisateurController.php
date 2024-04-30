<?php

namespace App\Controller;

use Exception;
use App\Form\AdminType;
use App\Form\NoRoleType;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport; // Corrected namespace
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{   private $utilisateurRepository;

    public function __construct(UtilisateurRepository $utilisateurRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
    }
    #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]
public function index(Request $request, UtilisateurRepository $utilisateurRepository): Response
{       /* $user = $this->getUser();

    // Check if a user is authenticated
    //if ($user) {
        // Get the user ID
      //  $userId = $user->getIdUtilisateur();
    } else {
        // Handle the case where no user is authenticated
        // You can redirect the user to the login page or perform any other action
        // For example:
        return $this->redirectToRoute('login');
    }*/
    // Retrieve the search query from the request
    $userId = $request->getSession()->get('id_utilisateur');
    $userName = $request->getSession()->get('nom');
    if (!$userId) {
        // If no user ID is found in the session, redirect to the login page
        return $this->redirectToRoute('login');
    }

    $searchQuery = $request->query->get('search');

    // Retrieve the sort order from the request
    $sortOrder = $request->query->get('sort');

    // Fetch utilisateurs based on search query and sorting order
    $utilisateurs = $utilisateurRepository->findBySearchAndSort($searchQuery, $sortOrder);

    return $this->render('utilisateur/index.html.twig', [
        'utilisateurs' => $utilisateurs,
        'searchQuery' => $searchQuery, // Pass searchQuery to the template
        'sortOrder' => $sortOrder, // Pass sortOrder to the template
    ]);
}





    
#[Route('/login', name: 'login', methods: ['GET', 'POST'])]
public function login(Request $request, UtilisateurRepository $userRepository, SessionInterface $session): Response
{
    // Check if the request is a POST to handle form submission
    if ($request->isMethod('POST')) {
        $cin = $request->request->get('cin');
        $mdp = $request->request->get('password');
        $rememberMe = $request->request->get('remember_me');

        if (empty($cin)) {
            $error = 'CIN is required.';
            return $this->render('utilisateur/login.html.twig', ['error' => $error]);
        }

        // Find the user by credentials using the repository method
        $user = $userRepository->findUserByCredentials($cin, $mdp);

        if ($user) {
            $session->set('id_utilisateur', $user->getIdUtilisateur());
            $session->set('cin',$user->getCin());
            $session->set('nom', $user->getNom());
            $session->set('prenom', $user->getPrenom());
            $session->set('adresse',$user->getAdresse());
            $session->set('role',$user->getRole());

            // Store last username and password if "Remember Me" is checked
            if ($rememberMe) {
                $session->set('last_cin', $cin);
                $session->set('last_password', $mdp);
            }/*else {
                // Clear last username and password if "Remember Me" is not checked
                $session->remove('last_cin');
                $session->remove('last_password');
            }*/

            return $this->redirectToRoute('app_utilisateur_index');
        } else {
            $error = 'Invalid credentials. Please try again.';
            return $this->render('utilisateur/login.html.twig', ['error' => $error]);
        }
    }

    return $this->render('utilisateur/login.html.twig');
}


#[Route('/forgot_password', name: 'forgot_password')]
public function forgotPassword(Request $request, UtilisateurRepository $userRepository, MailerInterface $mailer): Response
{
    if ($request->isMethod('POST')) {
        $cin = $request->request->get('cin');
        if (empty($cin)) {
            $error = 'CIN is required.';
            return $this->render('utilisateur/forgot_password.html.twig', ['error' => $error]);
        }

        $user = $userRepository->findOneByCin($cin);
        if ($user) {
            // Generate four random numbers
            $verificationCode = mt_rand(1000, 9999);

            // Send email with verification code
           // Load MAILER_DSN from environment variables
        $mailerDsn = $_ENV['MAILER_DSN'] ?? null;

        $email = (new Email())
            ->from('innohire45@gmail.com')
            ->to($user->getAdresse())
            ->subject('Code')
            ->text('code= '.$verificationCode);

        try {
            // Check if MAILER_DSN is set
            if (!$mailerDsn) {
                throw new \InvalidArgumentException("MAILER_DSN is not configured.");
            }

            // Create a new mailer instance with the provided DSN
            $transport = Transport::fromDsn($mailerDsn);
            $customMailer = new Mailer($transport);

            // Send the email
            $customMailer->send($email);
            $session = $request->getSession();
            $session->set('id_utilisateur', $user->getIdUtilisateur());
            $session->set('cin',$user->getCin());
            $session->set('nom', $user->getNom());
            $session->set('prenom', $user->getPrenom());
            $session->set('adresse',$user->getAdresse());
            $session->set('mdp',$user->getMdp());
            $session->set('role',$user->getRole());
            $session->set('OTP', $verificationCode);

            $responseMessage = 'Email sent successfully!';
        } catch (TransportExceptionInterface $e) {
            $responseMessage = 'Failed to send email: ' . $e->getMessage();
        } catch (\InvalidArgumentException $e) {
            $responseMessage = $e->getMessage();
        }

        //return new Response($responseMessage);
    

            // Redirect to a page where the user can enter the verification code
            return $this->render('utilisateur/check_otp.html.twig');
        } else {
            // Show error message if user with provided CIN is not found
            $error = 'Invalid CIN. Please try again.';
            return $this->render('utilisateur/forgot_password.html.twig', ['error' => $error]);
        }
    }

    return $this->render('utilisateur/forgot_password.html.twig');
}


#[Route('/check_otp', name: 'check_otp')]
public function checkOtp(Request $request, UtilisateurRepository $userRepository): Response
{
    if ($request->isMethod('POST')) {
        $otp = $request->request->get('otp');
        if (empty($otp)) {
            $error = 'OTP is required.';
            return $this->render('utilisateur/check_otp.html.twig', ['error' => $error]);
        }
        
        $session = $request->getSession();
        $mailOTP = $session->get('OTP');
        
        if ($otp != $mailOTP) {
            $error = 'Invalid OTP.';
            return $this->render('utilisateur/check_otp.html.twig', ['error' => $error]);
        }

        // OTP is correct, proceed with further actions
        return $this->render('utilisateur/change_mdp.html.twig');

        // For example, you can clear the OTP from the session
       // $session->remove('OTP');

        // Redirect the user to a success page or perform other actions
    }

    return $this->render('utilisateur/check_otp.html.twig');
}


#[Route('/change_mdp', name: 'change_mdp')]
public function changeMdp(Request $request, UtilisateurRepository $userRepository, EntityManagerInterface $entityManager): Response
{
    if ($request->isMethod('POST')) {
        $mdp = $request->request->get('mdp');
        $mdpVerif = $request->request->get('mdp_verif');

        if (empty($mdp) || empty($mdpVerif)) {
            $error = 'Both password fields are required.';
            return $this->render('utilisateur/change_mdp.html.twig', ['error' => $error]);
        }
        
        if ($mdp !== $mdpVerif) {
            $error = 'Passwords do not match.';
            return $this->render('utilisateur/change_mdp.html.twig', ['error' => $error]);
        }
        
        // Retrieve the idUtilisateur from the session
        $session = $request->getSession();
        $idUtilisateur = $session->get('id_utilisateur');
        
        // Fetch the user entity from the database using idUtilisateur
        $user = $userRepository->find($idUtilisateur);
        
        if (!$user) {
            $error = 'User not found.';
            return $this->render('utilisateur/change_mdp.html.twig', ['error' => $error]);
        }
        
        // Set the new password for the user entity
        $user->setMdp($mdp);
        
        // Persist the changes to the database
        $entityManager->flush();

        // Redirect the user to a success page or perform other actions
        return $this->render('utilisateur/login.html.twig');
    }

    return $this->render('utilisateur/change_mdp.html.twig');
}








#[Route('/my-profile', name: 'app_utilisateur_my_profile', methods: ['GET', 'POST'])]
public function myProfile(Request $request, UtilisateurRepository $userRepository): Response
{
    // Get the ID of the connected user from the session
    $userId = $request->getSession()->get('id_utilisateur');

    // Find the user by ID
    $user = $userRepository->find($userId);

    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    // Create and handle the form to edit user information
    $form = $this->createForm(NoRoleType::class, $user);
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
            $user->setImage($imageName);
        }
        
        // Persist changes to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Update session with the new value of 'nom'
        $request->getSession()->set('id_utilisateur', $user->getIdUtilisateur());
        $request->getSession()->set('nom', $user->getNom());
        $request->getSession()->set('prenom', $user->getPrenom());
        $request->getSession()->set('adresse', $user->getAdresse());
        $request->getSession()->set('mdp', $user->getMdp());
        $request->getSession()->set('role', $user->getRole());

        // Redirect back to the profile page
        return $this->redirectToRoute('app_utilisateur_index');
    }

    // Render the template with the user's information and edit form
    return $this->render('utilisateur/my_profile.html.twig', [
        'user' => $user,
        'form' => $form->createView(),
    ]);
}
           



    #[Route('/register', name: 'app_utilisateur_register', methods: ['GET', 'POST'])]
    public function register(Request $request): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
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
