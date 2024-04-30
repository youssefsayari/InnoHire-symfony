<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('question')
        ->add('choix')
        ->add('reponse_correcte')
        ->add('id_quiz', EntityType::class, [
            'class' => 'App\Entity\Quiz',
            'choice_label' => 'code_quiz',
            'choice_value' => 'id', // Utiliser l'ID comme valeur du champ
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'constraints' => [
                new Assert\Valid(), // Activer la validation automatique
            ],
        ]);
    }
}
