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
                'attr' => [
                    'style' => '
                        border : 1px solid white; 
                        border-radius: 0.25rem; 
                        line-height: 1.25; 
                        padding-top: 0.5rem;     
                        padding-bottom: 0.5rem;
                        padding-left: 0.75rem;
                        padding-right: 0.75rem;
                    '
                ] 
            ])
            ->add('latitude', TextType::class, [
                'label' => 'Latitude',
                'attr' => [
                    'style' => '
                        border : 1px solid white; 
                        border-radius: 0.25rem; 
                        line-height: 1.25; 
                        padding-top: 0.5rem;     
                        padding-bottom: 0.5rem;
                        padding-left: 0.75rem;
                        padding-right: 0.75rem;
                    '
                ] 
            ])
            ->add('longitude', TextType::class, [
                'label' => 'Longitude',
                'attr' => [
                    'style' => '
                        border : 1px solid white; 
                        border-radius: 0.25rem; 
                        line-height: 1.25; 
                        padding-top: 0.5rem;     
                        padding-bottom: 0.5rem;
                        padding-left: 0.75rem;
                        padding-right: 0.75rem;
                    '
                ] 
            ])
            ->add('date_start', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => [
                    'style' => '
                        border : 1px solid white; 
                        border-radius: 0.25rem; 
                        line-height: 1.25; 
                        padding-top: 0.5rem;     
                        padding-bottom: 0.5rem;
                        padding-left: 0.75rem;
                        padding-right: 0.75rem;
                    '
                ] 
            ])
            ->add('date_end', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'attr' => [
                    'style' => '
                        border : 1px solid white; 
                        border-radius: 0.25rem; 
                        line-height: 1.25; 
                        padding-top: 0.5rem;     
                        padding-bottom: 0.5rem;
                        padding-left: 0.75rem;
                        padding-right: 0.75rem;
                    '
                ] 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CheckPoint::class,
        ]);
    }
}
