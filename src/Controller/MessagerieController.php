<?php

namespace App\Controller;

use App\Entity\Messagerie;
use App\Form\MessagerieType;
use App\Repository\MessagerieRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

            return $this->redirectToRoute('app_messagerie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('messagerie/edit.html.twig', [
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
    
}
