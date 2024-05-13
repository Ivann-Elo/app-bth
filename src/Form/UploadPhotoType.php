<?php

namespace App\Form;

use App\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UploadPhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('imageFile', VichFileType::class, [
            'required' => true,
            'allow_delete' => true,
            'label' => false,
            'attr' => [
                'class' => 'form-control-file',
            ],
            'constraints' => [
                new File([
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/pjpeg',
                        'image/webp',
                    ],
                    'mimeTypesMessage' => 'Merci de téléverser un fichier JPG, JPEG ou WEBP valide.',
                ])
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }
}
