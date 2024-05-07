<?php
// src/Service/MessageGenerator.php
namespace App\Service;

use Twilio\Rest\Client;

class SmsGenerator
{
    
    public function SendSms(string $number, string $name, string $text)
    {
        
        $accountSid = $_ENV['twilio_account_sid'];  
        $authToken = $_ENV['twilio_auth_token'];
        $fromNumber = $_ENV['twilio_from_number']; 

        $toNumber = $number; 

        
        $message = ' '.$name . ' a envoyé le commentaire suivant : ' . $text . '. Ce commentaire a été supprimé en raison de l\'utilisation de mots inappropriés. ';


        
        $client = new Client($accountSid, $authToken);

        $client->messages->create(
            $toNumber,
            [
                'from' => $fromNumber,
                'body' => $message,
            ]
        );


    }
}