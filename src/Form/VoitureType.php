<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Voiture;
use phpDocumentor\Reflection\PseudoTypes\PositiveInteger;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_categorie', EntityType::class, [
                'label' => 'Categorie',
                'class' => Categorie::class,
                'choice_label' => 'libelle',

            ])
            ->add('model')
            ->add('marque')
            ->add('couleur')
            ->add('capacite', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('immat')
            ->add('prix', MoneyType::class)
            ->add('images', FileType::class,[
                'label' => 'Image (JPG or PNG)',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
