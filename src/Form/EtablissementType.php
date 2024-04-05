<?php

namespace App\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Utilisateur;

use App\Entity\Etablissement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;





use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class EtablissementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', null, [
            'attr' => [
                'class' => 'block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input',
                'placeholder' => 'nom'
            ]
        ])

             ->add('lieu', null, [
            'attr' => [
                'class' => 'block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input',
                'placeholder' => 'lieu'
            ]
        ])
        ->add('code_etablissement', null, [
            'attr' => [
                'class' => 'block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input',
                'placeholder' => 'code_etablissement'
            ]
        ])


     // Dans votre formulaire :
->add('type_etablissement', ChoiceType::class, [
    'choices' => [
        'École' => 'ecole',
        'Collège' => 'college',
        'Lycée' => 'lycee',
        'Faculté' => 'faculte',
    ],
    'expanded' => true, // Rend les options sous forme de boutons radio
    'multiple' => false, // Un seul choix est autorisé
    'choice_attr' => function($choice, $key, $value) {
        return ['style' => 'margin-left: 20px; margin-bottom: 10px']; // Ajoute un style inline pour espacer les boutons radio
    },
    'attr' => [
        'class' => 'block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input',
        'placeholder' => 'Type'
    ]
])

        
->add('image', null, [
    'attr' => [
        'class' => 'block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input',
        'placeholder' => 'Image Name',
        'readonly' => true // Ajouter cette ligne pour rendre le champ en lecture seule
    ]
])




            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'nom', // ou tout autre propriété de Utilisateur que vous souhaitez afficher
                'placeholder' => 'Choisissez un utilisateur', // texte à afficher pour le choix vide
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etablissement::class,
        ]);
    }
}
