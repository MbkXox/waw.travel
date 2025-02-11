<?php

namespace App\Form;

use App\Entity\CheckPoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckPointType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom de l\'étape',
                'label_attr' => [
                    'style' => 'display: block; font-weight: bold; margin-bottom: 0.5rem;',
                ],
                'attr' => [
                    'style' => '
                        width: 50%;
                        border: 1px solid white; 
                        border-radius: 0.25rem; 
                        line-height: 1.25; 
                        padding: 0.5rem 0.75rem; 
                        background-color: #f9f9f9; 
                        color: #333;
                    ',
                ],
            ])
            ->add('latitude', TextType::class, [
                'label' => 'Latitude',
                'label_attr' => [
                    'style' => 'display: block; font-weight: bold; margin-bottom: 0.5rem;',
                ],
                'attr' => [
                    'style' => '
                        width: 50%;
                        border: 1px solid white; 
                        border-radius: 0.25rem; 
                        line-height: 1.25; 
                        padding: 0.5rem 0.75rem; 
                        background-color: #f9f9f9; 
                        color: #333;
                    ',
                ],
            ])
            ->add('longitude', TextType::class, [
                'label' => 'Longitude',
                'label_attr' => [
                    'style' => 'display: block; font-weight: bold; margin-bottom: 0.5rem;',
                ],
                'attr' => [
                    'style' => '
                        width: 50%;
                        border: 1px solid white; 
                        border-radius: 0.25rem; 
                        line-height: 1.25; 
                        padding: 0.5rem 0.75rem; 
                        background-color: #f9f9f9; 
                        color: #333;
                    ',
                ],
            ])
            ->add('date_start', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'label_attr' => [
                    'style' => 'display: block; font-weight: bold; margin-bottom: 0.5rem;',
                ],
                'attr' => [
                    'style' => '
                        width: 50%;
                        border: 1px solid white; 
                        border-radius: 0.25rem; 
                        line-height: 1.25; 
                        padding: 0.5rem 0.75rem; 
                        background-color: #f9f9f9; 
                        color: #333;
                    ',
                ],
            ])
            ->add('date_end', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'label_attr' => [
                    'style' => 'display: block; font-weight: bold; margin-bottom: 0.5rem;',
                ],
                'attr' => [
                    'style' => '
                        width: 50%;
                        border: 1px solid white; 
                        border-radius: 0.25rem; 
                        line-height: 1.25; 
                        padding: 0.5rem 0.75rem; 
                        background-color: #f9f9f9; 
                        color: #333;
                    ',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CheckPoint::class,
        ]);
    }
}