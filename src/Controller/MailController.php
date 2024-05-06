<?php

// src/Controller/MailController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport; // Corrected namespace
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    #[Route('/test_mail', name: 'test_mail')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        // Load MAILER_DSN from environment variables
        $mailerDsn = $_ENV['MAILER_DSN'] ?? null;

        $email = (new Email())
            ->from('innohire45@gmail.com')
            ->to('kthiri.amenallah02@gmail.com')
            ->subject('Test Email')
            ->text('KHIDMET.');

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
            $responseMessage = 'Email sent successfully!';
        } catch (TransportExceptionInterface $e) {
            $responseMessage = 'Failed to send email: ' . $e->getMessage();
        } catch (\InvalidArgumentException $e) {
            $responseMessage = $e->getMessage();
        }

        return new Response($responseMessage);
    }
}
