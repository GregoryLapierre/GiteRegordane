<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control shadow',
                    'readonly' => true
                ],
                'label_attr' => [
                    'class' => 'visually-hidden'
                ]
            ])
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control shadow',
                    'readonly' => true
                ],
                'label_attr' => [
                    'class' => 'visually-hidden'
                ]
            ])
            ->add('number_person', IntegerType::class, [
                'label_attr' => [
                    'class' => 'visually-hidden'
                ],
                'attr' => [
                    'class' => 'form-control shadow',
                    'min' => 1
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
