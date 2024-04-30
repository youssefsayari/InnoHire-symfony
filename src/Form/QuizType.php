<?php

namespace App\Form;

use App\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code_quiz', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Code Quiz cannot be blank']),
                ],
            ])
            ->add('nom_quiz', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Nom Quiz cannot be blank']),
                ],
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Niveau cannot be blank']),
                    new Choice(['choices' => ['Facile', 'Moyen', 'Difficile'], ]),
                ],
            ])
            ->add('prix_quiz', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Prix Quiz cannot be blank']),
                ],
            ])
            ->add('image_quiz', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Image Quiz cannot be blank']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
