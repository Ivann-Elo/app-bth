<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Intervention;
use App\Entity\user;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjoutCategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCat')
            ->add('createdBy', EntityType::class, [
                'class' => user::class,
                'choice_label' => 'id',
            ])
            ->add('idInter', EntityType::class, [
                'class' => Intervention::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
