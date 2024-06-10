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
                'attr' => [
                    'placeholder' => 'Description de l\'intervention',
                    'class' => 'form-control'
                ]
            ])
            ->add('note', TextType::class, [
                'label' => 'Note de l\'intervention',
                'attr' => [
                    'placeholder' => 'note de l\'intervention',
                    'class' => 'form-control'
                ]
            ])
            ->add('villeInter', TextType::class, [
                'label' => 'Ville de l\'intervention',
                'attr' => [
                    'placeholder' => 'Ville de l\'intervention',
                    'class' => 'form-control'
                ]
            ])
            ->add('zipInter', TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Code postal',
                    'class' => 'form-control'
                ]
            ])
            ->add('rueInter', TextType::class, [
                'label' => 'Adresse de l\'intervention',
                'attr' => [
                    'placeholder' => 'Adresse de l\'intervention',
                    'class' => 'form-control'
                ]
            ])
            ->add('dateCreation', HiddenType::class)
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dateFin', DateType::class,[
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Terminée' => 'Terminée',
                    'En attente' => 'En attente'
                ],
                'label' => 'Statut de l\'intervention',
                'attr' => [
                    'class' => 'form-control'
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
