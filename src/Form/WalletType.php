<?php

namespace App\Form;



use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\DataTransformerInterface;





use App\Entity\Wallet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Etablissement;

class WalletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {







        $builder
        ->add('balance', null, [
            

            'attr' => [
                'class' => 'block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input',
                'placeholder' => 'balance'
            ]
        ])
        
            
            ->add('date_c', DateTimeType::class, [
                'widget' => 'single_text',
                // cela définit la valeur à la date et l'heure actuelles
                'data' => new \DateTime(),
                // format personnalisé pour l'affichage de la date et de l'heure
                
                'attr' => [
                    'class' => 'block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input',
                    'placeholder' => 'jj/mm/année hh:mm',
                    'readonly' => true, // rend le champ en lecture seule
                ],
            ]);



            $builder->add('etablissement', EntityType::class, [
                'class' => Etablissement::class,
                'choice_label' => 'nom', // Assuming 'id' is a meaningful property of Etablissement
                
                'attr' => [
                    'class' => 'block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray',
                ]
                ])





            
                ->add('status', CheckboxType::class, [

                    'label' => 'Status',
                    'required' => false,
                ])
                
                // Ajouter un transformateur de données pour gérer la conversion de la propriété de statut
                ->get('status')->addModelTransformer(new class implements DataTransformerInterface {
                    public function transform($value)
                    {
                        // Transform the integer value to boolean
                        return ($value == 1) ? true : false;
                    }
                
                    public function reverseTransform($value)
                    {
                        // Transform the boolean value to integer
                        return $value ? 1 : 0;
                    }
                });


            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wallet::class,
        ]);
    }
}
