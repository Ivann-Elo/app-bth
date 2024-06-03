<?php

namespace App\Form;

use App\Entity\Client;   
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ModifClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mail', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'form-control',
                    'required' => 'required'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/',
                        'message' => 'Veuillez entrer une adresse email valide.'
                    ]),
                ],
                'label' => false
            ])
            ->add('telephone',null, [
                'attr' => [
                    'placeholder' => 'Téléphone',
                    'class' => 'form-control',
                    'type' => 'tel',
                    'required' => 'required'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\+?[0-9\s\-\(\)]+$/',
                        'message' => 'Veuillez entrer un numéro de téléphone valide.',
                    ]),
                ],
                'label' => false
            ])
            ->add('rueClient',null, [
                'attr' => [
                    'placeholder' => 'Rue',
                    'class' => 'form-control',
                    'required' => 'required'
                ],
                'label' => false
            ])
            ->add('zipClient',null, [
                'attr' => [
                    'placeholder' => 'Code postal',
                    'class' => 'form-control',
                    'required' => 'required',
                    'type' => 'zipcode'
                ],
                'label' => false
            ])
            ->add('villeClient',null, [
                'attr' => [
                    'placeholder' => 'Ville',
                    'class' => 'form-control',
                    'required' => 'required',
                    'type' => 'city'
                ],
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
