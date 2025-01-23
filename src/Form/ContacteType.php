<?php
namespace App\Form;

use App\Entity\Contacte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\NotBlank; 
use Symfony\Component\Validator\Constraints\Regex; 

class ContacteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom complet',
                'constraints' => [
                new NotBlank(['message' => 'Le nom est obligatoire.']),
                new Regex([
                    'pattern' => '/^[A-Za-zÀ-ÿ\s]+$/u',
                    'message' => 'Seules les lettres sont autorisées dans le nom.',
                ]),
            ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet',
                'constraints' => [
                new NotBlank(['message' => 'Le sujet est obligatoire.']),
                new Regex([
                    'pattern' => '/^[A-Za-zÀ-ÿ\s]+$/u',
                    'message' => 'Seules les lettres sont autorisées dans le sujet.',
                ]),
            ],
            ])

           
            ->add('phone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Votre numéro de téléphone',
                    'pattern' => '^\+?[0-9\s\-]{7,15}$',
                    'title' => 'Veuillez entrer un numéro de téléphone valide (ex : +123456789 ou 123-456-7890)',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le numéro de téléphone est obligatoire.']),
                    new Regex([
                        'pattern' => '/^\+?[0-9\s\-]{7,15}$/',
                        'message' => 'Veuillez entrer un numéro de téléphone valide.',
                    ]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'constraints' => [
                new NotBlank(['message' => 'Le sujet est obligatoire.']),
                new Regex([
                    'pattern' => '/^[A-Za-zÀ-ÿ\s]+$/u',
                    'message' => 'Seules les lettres sont autorisées dans le sujet.',
                ]),
            ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contacte::class,
        ]);
    }
}
