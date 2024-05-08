<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', null, [
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('titre', null, [
            'constraints' => [
                new NotBlank(),
            ],
        ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
              /*  'attr' => [
                    'class' => 'block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray',
                    'rows' => '3',
                    'placeholder' => 'Enter some long form description'
                ]*/
            ])
           /* ->add('date', DateTimeType::class, [
                'data' => new \DateTime(), // Set default value to current date
            ])*/
            ->add('status', HiddenType::class, [
                'data' => 0, // Set the default value of status to 0
            ])
            //->add('id_post')
            /*->add('id_utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'nom', // Assuming you want to display the user's name
                // You can customize other options like placeholder, required, etc.
            ]);*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
