<?php

namespace App\Form;

use App\Entity\RoadTrip;
use App\Entity\Type;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoadTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
            ])
            ->add('body', TextareaType::class, [
                'attr' => ['rows' => 10],
                'label' => false,
            ])
            ->add('status', CheckboxType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'label' => false,
                'choice_label' => 'title',
            ])
            ->add('coverImage', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
            ])
            ->add('medias', CollectionType::class, [
                'entry_type' => MediaType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'mapped' => false,
            ])
            ->add('checkPoints', CollectionType::class, [
                'entry_type' => CheckPointType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RoadTrip::class,
        ]);
    }
}
