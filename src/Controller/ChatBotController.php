<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ChatBotController extends AbstractController
{
    #[Route('/chat', name: 'chat')]
    public function chat(Request $request): Response
    {
        return $this->render('chatbot/chatbot.html.twig', [

        ]);
    }
}
 