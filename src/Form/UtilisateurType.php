<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Cin cannot be blank']),
                ],
            ])
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Nom  cannot be blank']),
                ],
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Prenom  cannot be blank']),
                ],
            ])
            ->add('adresse', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Email  cannot be blank']),
                ],
            ])
            ->add('mdp', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'mdp  cannot be blank']),
                ],
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Admin' => 0,
                    'Representant' => 1,
                    'Candidat' => 2,
                ],
                'expanded' => true, // Optionally, if you want radio buttons instead of a dropdown
                'multiple' => false, // Optionally, if you want to allow selecting multiple roles
            ])
           // ->add('image')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
