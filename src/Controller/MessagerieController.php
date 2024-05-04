<?php

namespace App\Controller;

use App\Entity\Messagerie;
use App\Form\MessagerieType;
use App\Repository\MessagerieRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


#[Route('/messagerie')]
class MessagerieController extends AbstractController
{
    #[Route('/', name: 'app_messagerie_index', methods: ['GET'])]
    public function index(MessagerieRepository $messagerieRepository): Response
    {
        return $this->render('messagerie/index.html.twig', [
            'messageries' => $messagerieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_messagerie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $messagerie = new Messagerie();
        
        // Handle file upload
       $file = $request->files->get('file');
        if ($file instanceof UploadedFile) {
            // Get the original file name and set it as the contenu
            $messagerie->setContenu($file->getClientOriginalName());
            // Set the type to "file"
            $messagerie->setType('file');
        } else {
            // If no file is uploaded, set type to "text"
            $messagerie->setType('text');
        }
       // $messagerie->setType('file');
        // Set the default value for the date field as a DateTime object
        $messagerie->setDate(new \DateTime());
        
        $form = $this->createForm(MessagerieType::class, $messagerie);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($messagerie);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_messagerie_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('messagerie/new.html.twig', [
            'messagerie' => $messagerie,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_messagerie_show', methods: ['GET'])]
    public function show(Messagerie $messagerie): Response
    {
        return $this->render('messagerie/show.html.twig', [
            'messagerie' => $messagerie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_messagerie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Messagerie $messagerie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MessagerieType::class, $messagerie);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            // Assuming $senderId and $reciverId are available
            $senderId = $messagerie->getSender()->getIdUtilisateur();
            $reciverId = $messagerie->getReciver()->getIdUtilisateur();
            //dd($form->getErrors(true));
    
            return $this->redirectToRoute('app_messagerie_messages', [
                'senderId' => $senderId,
                'reciverId' => $reciverId,
            ], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('messagerie/edit.html.twig', [
            'messagerie' => $messagerie,
            'form' => $form,
        ]);
    }
    /////////////////////////////////////////////////////Front ////////////////////////////////////////////////////////////////////////////
    #[Route('/{id}/editfront', name: 'app_messagerie_editfront', methods: ['GET', 'POST'])]
    public function editfront(Request $request, Messagerie $messagerie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MessagerieType::class, $messagerie);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            // Assuming $senderId and $reciverId are available
            $senderId = $messagerie->getSender()->getIdUtilisateur();
            $reciverId = $messagerie->getReciver()->getIdUtilisateur();
            //dd($form->getErrors(true));
    
            return $this->redirectToRoute('app_messagerie_messagesfront', [
                'senderId' => $senderId,
                'reciverId' => $reciverId,
            ], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('messagerie/editfront.html.twig', [
            'messagerie' => $messagerie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_messagerie_delete', methods: ['POST'])]
    public function delete(Request $request, Messagerie $messagerie, EntityManagerInterface $entityManager): Response
    {
        $senderId = $messagerie->getSender()->getIdUtilisateur();
        $reciverId = $messagerie->getReciver()->getIdUtilisateur();

        if ($this->isCsrfTokenValid('delete' . $messagerie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($messagerie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_messagerie_messages', ['senderId' => $senderId, 'reciverId' => $reciverId], Response::HTTP_SEE_OTHER);
    }

    /////////////////////////////////////////////////Front/////////////////////////////////////////////////////////////////////////////////
    #[Route('/{id}/delete', name: 'app_messagerie_deletefront', methods: ['POST'])]
    public function deletefront(Request $request, Messagerie $messagerie, EntityManagerInterface $entityManager): Response
    {
        $senderId = $messagerie->getSender()->getIdUtilisateur();
        $reciverId = $messagerie->getReciver()->getIdUtilisateur();

        if ($this->isCsrfTokenValid('deletefront' . $messagerie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($messagerie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_messagerie_messagesfront', ['senderId' => $senderId, 'reciverId' => $reciverId], Response::HTTP_SEE_OTHER);
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    #[Route('/messages/{senderId}/{reciverId}', name: 'app_messagerie_messages')]
    public function getMessagesBySenderreciver(int $senderId, int $reciverId, MessagerieRepository $messagerieRepository, Request $request, EntityManagerInterface $entityManager, UtilisateurRepository $utilisateurRepository): Response
    {
        // Fetch sender and reciver entities from the repository
        $sender = $utilisateurRepository->find($senderId);
        $reciver = $utilisateurRepository->find($reciverId);
    
        $messages = $messagerieRepository->findMessagesBySenderreciverOrderedByDateDesc($senderId, $reciverId);
    
        $messagerie = new Messagerie();
        
        // Set the default value for the date field as a DateTime object
        $messagerie->setDate(new \DateTime());
        // Set the sender and reciver directly
        $messagerie->setSender($sender);
        $messagerie->setReciver($reciver);
    
        $form = $this->createForm(MessagerieType::class, $messagerie);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle form submission
            $entityManager->persist($messagerie);
            $entityManager->flush();
            // Redirect after form submission
            return $this->redirectToRoute('app_messagerie_messages', [
                'senderId' => $senderId,
                'reciverId' => $reciverId,
            ], Response::HTTP_SEE_OTHER);
            
        }
    
        return $this->render('messagerie/messages.html.twig', [
            'messages' => $messages,
            'messagerie' => $messagerie,
            'form' => $form->createView(),
        ]);
    }

////////////////////////////////////////////////////////////////////Front ////////////////////////////////////////////////////////////////
#[Route('/messagesfront/{senderId}/{reciverId}', name: 'app_messagerie_messagesfront')]
public function getMessagesBySenderreciverfront(int $senderId, int $reciverId, MessagerieRepository $messagerieRepository, Request $request, EntityManagerInterface $entityManager, UtilisateurRepository $utilisateurRepository): Response
{
    // Fetch sender and reciver entities from the repository
    $sender = $utilisateurRepository->find($senderId);
    $reciver = $utilisateurRepository->find($reciverId);

    $messages = $messagerieRepository->findMessagesBySenderreciverOrderedByDateDesc($senderId, $reciverId);

    $messagerie = new Messagerie();
    
    // Set the default value for the date field as a DateTime object
    $messagerie->setDate(new \DateTime());
    // Set the sender and reciver directly
    $messagerie->setSender($sender);
    $messagerie->setReciver($reciver);

    $form = $this->createForm(MessagerieType::class, $messagerie);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle form submission
        $entityManager->persist($messagerie);
        $entityManager->flush();
        // Redirect after form submission
        return $this->redirectToRoute('app_messagerie_messagesfront', [
            'senderId' => $senderId,
            'reciverId' => $reciverId,
        ], Response::HTTP_SEE_OTHER);
        
    }

    return $this->render('messagerie/messagesfront.html.twig', [
        'messages' => $messages,
        'messagerie' => $messagerie,
        'form' => $form->createView(),
    ]);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

#[Route('/download/{fileName}', name: 'app_download_file')]
public function downloadFile(string $fileName): Response
{
    // Define the directory where the files are stored
    $fileDirectory = $this->getParameter('kernel.project_dir') . '\public\downloads';
    
    // Generate the file path
    $filePath = $fileDirectory . DIRECTORY_SEPARATOR . $fileName;
    
    // Create a new File object
    $file = new File($filePath);
    
    // Return a response with the file contents and specify the download location
    return $this->file($file, $fileName, ResponseHeaderBag::DISPOSITION_ATTACHMENT, null, true);
}



#[Route('/sendmessage', name: 'send_message', methods: ['POST'])]
public function sendMessage(): Response
{
    // WhatsApp Business Management API endpoint
    $apiUrl = 'https://graph.facebook.com/v19.0/248063518400933/messages';

    // Access token for authorization
    $accessToken = 'EAAP8XTZAu1t0BO5voIdSC5s2pr2bqqGXMA1v7wRBOAkyZBnZBv9LkNohDfgau5oPWXZBBIpHjG2W7ZCDR8OmXcHG5Ie6OHEjsxSCXy2PYhI5M8nserMfrKuZCqYRTGnMPQlZBFmnV8IYTrikorZBnVTJDApR1gn33ZBjJITZCy4fAUxO3Xi0W781ZCiIq7ZCEZCf9EOpvrZCbQzscvZC3VjgMsiQzaPXUtnI78F4pEZD';

    // Prepare the message payload
    $messagePayload = [
        'messaging_product' => 'whatsapp',
        'to' => '21626212515', // Replace with the recipient's phone number
        'type' => 'template',
        'template' => [
            'name' => 'hello_world',
            'language' => [
                'code' => 'en_US'
            ]
        ]
    ];

    // Create the HttpClient instance
    $httpClient = HttpClient::create();

    try {
        // Send the POST request to the API endpoint
        $response = $httpClient->request('POST', $apiUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ],
            'json' => $messagePayload // Convert the array to JSON format
        ]);

        // Handle the API response (e.g., check status code, parse JSON response)

        return new Response('Message sent successfully.', Response::HTTP_OK);
    } catch (\Exception $e) {
        // Handle any exceptions (e.g., network errors, API errors)
        return new Response('Error sending message: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

    
}
