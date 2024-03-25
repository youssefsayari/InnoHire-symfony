<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('audience')
            ->add('date')
            ->add('caption')
            ->add('image')
            ->add('totalReactions')
            ->add('nbComments')
            ->add('utilisateur', EntityType::class, ['class' => Utilisateur::class,
                             'choice_label' => 'id_utilisateur', 
                             // ou tout autre propriété de Utilisateur que vous souhaitez afficher
                                              'placeholder' => 'Choisissez un utilisateur', ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}