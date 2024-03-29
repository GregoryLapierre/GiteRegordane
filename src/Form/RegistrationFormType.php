<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control shadow'
                ],
                'label_attr' => [
                    'class' => 'mt-2'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un Email',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-z0-9-\.]+@([a-z0-9-]+\.)+[a-z]{2,3}$/',
                        'message' => 'Votre Email doit être au format xxxxxxxx@xxxxx.xx'
                    ])
                ]
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control shadow'
                ],
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un nom',
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Votre nom ne peut pas contenir de chiffres'
                    ])
                ]
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control shadow'
                ],
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'mt-2'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un prénom',
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Votre prénom ne peut pas contenir de chiffres'
                    ])
                ]
            ])
            ->add('phone', TextType::class, [
                'attr' => [
                    'class' => 'form-control shadow'
                ],
                'label' => 'Téléphone',
                'label_attr' => [
                    'class' => 'mt-2'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un numéro de téléphone',
                    ]),
                    new Regex([
                        'pattern' => '/^(0|\+33)[1-9](\s?[0-9]{2}){4}$/',
                        'message' => 'Votre numéro de téléphone doit être au format 0X XX XX XX XX ou +33X XX XX XX XX (les espaces ne sont pas nécessaires)'
                    ])
                ]
            ])
            ->add('adress', TextType::class, [
                'attr' => [
                    'class' => 'form-control shadow'
                ],
                'label' => 'Adresse',
                'label_attr' => [
                    'class' => 'mt-2'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une adresse',
                    ])
                ]
            ])
            ->add('postal', TextType::class, [
                'attr' => [
                    'class' => 'form-control shadow'
                ],
                'label' => 'Code postal',
                'label_attr' => [
                    'class' => 'mt-2'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un code postal',
                    ]),
                    new Regex([
                        'pattern' => '/^(0[1-9]|[1-8]\d|9[0-8])\d{3}$/',
                        'message' => 'Votre code postal doit comporter 5 chiffres au format XXXXX'
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control shadow'
                ],
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'mt-2'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une ville',
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Votre ville ne peut pas contenir de chiffres'
                    ])
                ]
            ])
            ->add('RGPDConsent', CheckboxType::class, [
                'mapped' => false,
                'label' => 'En soumettant ce formulaire, j\'accepte que les informations saisies soit traitées pour permettre la réservation de l\'hébergement et le paiement en ligne.',
                'label_attr' => [
                    'class' => 'mt-2'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control shadow'
                ],
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'mt-2'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/',
                        'message' => 'Votre mot de passe doit comporter au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
