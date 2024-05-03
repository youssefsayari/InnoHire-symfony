<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Cin cannot be blank']),
                    new Positive(['message' => 'Cin must be a positive number']),
                  
                ],
            ])
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Nom cannot be blank']),
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Nom must not contain numbers',
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Prenom cannot be blank']),
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Prenom must not contain numbers',
                    ]),
                ],
            ])
            ->add('adresse', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Adresse cannot be blank']),
                    new Email(['message' => 'Adresse must be a valid email address']),
                ],
            ])
            ->add('mdp', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'mdp  cannot be blank']),
                ],
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    
                    'Representant' => 1,
                    'Candidat' => 2,
                ],
                'expanded' => true, // Optionally, if you want radio buttons instead of a dropdown
                'multiple' => false, // Optionally, if you want to allow selecting multiple roles
            ])
            ->add('image', FileType::class, [
                'label' => 'User Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (jpeg, png, gif)',
                    ]),
                ],
            ])
           ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'register',
                
            ])
            ;
            
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => 'cin',
                    'message' => 'Cin must be unique',
                ]),
            ],
        ]);
    }
}
