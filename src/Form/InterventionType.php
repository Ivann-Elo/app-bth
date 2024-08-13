<?php

namespace App\Form;

use App\Entity\Intervention;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class InterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'label' => '',
                'label_attr' => [
                    'class' => 'mb-2'
                ],
                'attr' => [
                    'placeholder' => 'Description de l\'intervention',
                    'class' => 'form-control'
                ]
            ])
            ->add('note', TextType::class, [
                'label' => 'Note de l\'intervention',
                'label_attr' => [
                    'class' => 'mb-2'
                ],
                'attr' => [
                    'placeholder' => 'note de l\'intervention',
                    'class' => 'form-control'
                ]
            ])
            ->add('dateCreation', HiddenType::class)
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'label_attr' => [
                    'class' => 'mb-2'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dateFin', DateType::class,[
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'label_attr' => [
                    'class' => 'mb-2'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'En cours' => 'En cours',
                    'Archivée' => 'Archivée'
                ],
                'label' => 'Statut de l\'intervention',
                'label_attr' => [
                    'class' => 'mb-2'
                ],
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}
