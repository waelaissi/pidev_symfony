<?php

namespace App\Form;

use App\Data\SearchHotelData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchHotelForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])

            ->add('region', ChoiceType::class, [
                'choices'  => [
                    ' ' => '',
                    'Sousse' => 'Sousse',
                    'Bizeter' => 'Bizeter',
                    'Mednine' => 'Mednine',
                    'Nabeul' => 'Nabeul',
                    'Siliana' => 'Siliana',
                    'Jendouba' => 'Jendouba',
                    'Kairaouane' => 'Kairaouane',
                    'Kasserine' => 'Kasserine',
                    'Mahdia' => 'Mahdia',
                    'Monastir' => 'Monastir',
                    'Sfax' => 'Sfax',
                    'Zaghouan' => 'Zaghouan',
                    'Ben arous' => 'Ben arous',
                    'Gabes' => 'Gabes',
                    'Kebili' => 'Kebili',
                    'Tozeur' => 'Tozeur',
                    'Tunis' => 'Tunis',
                ],
                'required' => false,
                'label' => false,
            ])

            ->add('ville', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('nbEtoile', IntegerType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Etoile(s)'
                ]
            ])

        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchHotelData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}