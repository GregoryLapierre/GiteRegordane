<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control shadow'
                ],
                'label' => 'Nom',
                'constraints' => [
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
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Votre prénom ne peut pas contenir de chiffres'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control shadow'
                ],
                'label_attr' => [
                    'class' => 'mt-2'
                ],
            ])
            ->add('subject', TextType::class, [
                'attr' => [
                    'class' => 'form-control shadow'
                ],
                'label' => 'Sujet',
                'label_attr' => [
                    'class' => 'mt-2'
                ],
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control shadow',
                    'rows' => '8'
                ],
                'label_attr' => [
                    'class' => 'mt-2'
                ]
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
                'locale' => 'fr',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
