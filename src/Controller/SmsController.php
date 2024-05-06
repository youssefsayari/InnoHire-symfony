<?php

namespace App\Controller;

use App\Service\SmsGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\SessionInterface;


class SmsController extends AbstractController
{
   
    //La vue du formulaire d'envoie du sms
    #[Route('/sms', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('sms/index.html.twig',['smsSent'=>false]);
    }
    private $session;
    public $idUtilisateurConnecte;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->idUtilisateurConnecte = $this->session->get('id_utilisateur');
    }


    //Gestion de l'envoie du sms
    #[Route('/sendSms', name: 'send_sms', methods:'POST')]
    public function sendSms(Request $request, SmsGenerator $smsGenerator): Response
    {
       
        

        $name=$request->request->get('name');

        $text=$request->request->get('text');

        $number_test=$_ENV['twilio_to_number'];// Numéro vérifier par twilio. Un seul numéro autorisé pour la version de test.

        //Appel du service
        $smsGenerator->sendSms($number_test ,$name,$text);

        return $this->render('sms/index.html.twig', ['smsSent'=>true]);
    }
}
