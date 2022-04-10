<?php

namespace App\Form;

use App\Entity\Transaction;

use phpDocumentor\Reflection\PseudoTypes\PositiveInteger;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Positive;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCarte',TextType::class,[
                'attr'=>[
                        'id'=>"nomCarte",
                        'class'=>'form-control text_input',
                        'name'=>'firstname_booking',
                        'minlength' => 4
                    ]
            ])
            ->add('numeroCarte',TextType::class,[
                'attr'=>[
                    'id'=>"numeroCarte",
                    'class'=>'form-control number_input',
                    'name'=>'card_number',
                    'maxlength' => 16
                ],

            ])
            ->add('expMois',IntegerType::class,[
                'constraints' => [new Positive()],
                'attr'=>[
                    'id'=>"expMois",
                    'class'=>'form-control integer_input',
                    'name'=>'expire_month',
                    'placeholder'=>'MM',
                    'min'=>1,
                    'max'=>12,
                    'maxlength' => 2
                ],

            ])
            ->add('expAnnee',IntegerType::class,[
                'constraints' => [new Positive()],
                'attr'=>[
                    'id'=>"expAnnee",
                    'class'=>'form-control',
                    'name'=>'expire_year',
                    'min'=>date("Y"),
                    'placeholder'=>'Year',
                    'maxlength' => 4
                ],
            ])
            ->add('cvc',TextType::class,[
                'attr'=>[
                    'class'=>'form-control number_input',
                    'id'=>'cvc',
                    'name'=>'ccv',
                    'placeholder'=>'CCV',
                    'maxlength' => 3,
                ]
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
