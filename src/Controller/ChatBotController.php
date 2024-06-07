<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;

class ChatBotController extends AbstractController
{
    #[Route('/chat', name: 'chat', methods: ['GET', 'POST'])]
    public function chat(Request $request): Response
    {
        // If it's not a POST request, simply render the chat page
        if ($request->getMethod() !== 'POST') {
            return $this->render('chatbot/chatbot.html.twig');
        }

        $userMessage = $request->request->get('message');

        if (empty($userMessage)) {
            return new Response('User message is empty', Response::HTTP_BAD_REQUEST);
        }

        $client = new Client([
            'verify' => false, // Disable SSL verification
        ]);

        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
              'Authorization' => 'Bearer sk-proj-dCNUsgUD6uJPOOSX6GzKT3BlbkFJzREMzi220QhSgCF5yt6g', // Replace with your actual API key
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a ChatGPT assistant.'],
                    ['role' => 'user', 'content' => $userMessage],
                ],
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);
        
        // Extracting content from the response
        $botResponse = $responseData['choices'][0]['message']['content'];

        return new Response($botResponse, Response::HTTP_OK);
    }
}