<?php

namespace App\Form;

use App\Entity\Messagerie;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MessagerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('date')
            ->add('type', HiddenType::class, [ // Add type field as a HiddenType
                'data' => 'text', // Set the default value if needed
            ])
           /* ->add('type', HiddenType::class, [
                'data' => "text", // Set the default value of status to 0
            ])*/
            ->add('contenu', null, [
                'constraints' => [
                    new NotBlank()
                ],
                // Add other options if needed
            ]);
            /*->add('sender', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'nom', // Assuming you want to display the user's name
                // You can customize other options like placeholder, required, etc.
            ])
            ->add('reciver', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'nom', // Assuming you want to display the user's name
                // You can customize other options like placeholder, required, etc.
            ]);*/
            /*->add('sender', HiddenType::class, [
                'data' => $options['sender_id'], // Assuming you pass the current user ID from the controller
            ])
            ->add('reciver', HiddenType::class, [
                'data' => $options['reciver_id'], // Assuming you pass the receiver ID from the controller
            ]);*/
            //->add('sender')
            //->add('reciver')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Messagerie::class,
            'sender_id' => 1, // Add a default value for current_user_id option
            'reciver_id' => 2, // Add a default value for receiver_id option
        ]);
    }
}
