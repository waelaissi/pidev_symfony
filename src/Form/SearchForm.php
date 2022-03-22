<?php

namespace App\Form;

use App\Data\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])

            ->add('reg', ChoiceType::class, [
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
            ])

            ->add('min', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix min'
                ]
            ])
            ->add('max', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix max'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}