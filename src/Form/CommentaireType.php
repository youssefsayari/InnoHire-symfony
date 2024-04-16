<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Utilisateur;
use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description_co')
            
            ->add('date_co', DateTimeType::class, [
                'widget' => 'single_text',
                // cela définit la valeur à la date et l'heure actuelles
                'data' => new \DateTime(),
                'attr' => [
                    'class' => 'block w-full mt-1 text-sm dark:border-gray-600 
                    dark:bg-gray-700 focus:border-purple-400 focus:outline-none
                     focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input',
                    'placeholder' => 'jj/mm/année hh:mm',
                    'readonly' => true, // rend le champ en lecture seule
                ],
            ])
            ->add('post', EntityType::class, ['class' => Post::class,
            'choice_label' => 'id_post', 
            // ou tout autre propriété de Utilisateur que vous souhaitez afficher
                             'placeholder' => 'Choisissez une poste', ])
            ->add('utilisateur', EntityType::class, ['class' => Utilisateur::class,
                             'choice_label' => 'id_utilisateur', 
                             // ou tout autre propriété de Utilisateur que vous souhaitez afficher
                                              'placeholder' => 'Choisissez un utilisateur', ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}