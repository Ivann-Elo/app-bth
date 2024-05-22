<?php

namespace App\Form;

use App\Entity\Tache;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class AjoutTacheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class,[
                'attr' => [
                    'placeholder' => 'Ajout d\'une tâche',
                    'class' => 'form-control text-center my-3',
                    'required' => true,
                    'maxlength' => 50,
                    'minlength' => 5,
                ],
                'label' => false,
               
            ])
            ->add('idCat', HiddenType::class, [
                'data' => $options['categorie']->getId(),
                'mapped' => false, 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
            'categorie' => null,
        ]);
    }
}
