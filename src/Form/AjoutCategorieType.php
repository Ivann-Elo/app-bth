<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AjoutCategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCat', TextType::class, [
                'attr' => [
                    'placeholder' => '20 caractères maximum',
                    'class' => 'form-control text-center my-3',
                    'required' => true,
                    'maxlength' => 20,
                ],
                'label' => 'Nom de la catégorie',
                'label_attr' => [
                    'class' => 'roboto-medium'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
