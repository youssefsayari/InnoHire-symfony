<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Utilisateur;
use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description_co')
            ->add('date_co')
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