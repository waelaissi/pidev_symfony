<?php

namespace App\Form;

use App\Entity\Sujet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SujetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titresujet')
            ->add('contenu')
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (JPG or PNG)',
                'required' => true,
                'allow_delete' => true,
                'download_uri' => false,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sujet::class,
        ]);
    }
}
