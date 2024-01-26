<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

/**
 *
 */
class AddressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Quel nom souhaitez-vous donner à votre addresse ?',
                'attr' => [
                    'placeholder' => 'Nommez votre addresse',
                    'class' => 'form-control'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom',
                    'class' => 'form-control'
                ]
            ])
            ->add('lastname', TextType::class, [
        'label' => 'Nom',
        'attr' => [
            'placeholder' => 'votre nom',
            'class' => 'form-control'
        ]
    ])
            ->add('company', TextType::class, [
        'label' => 'Société',
        'required' => false,
        'attr' => [
            'placeholder' => 'Le nom de votre société si vous en avez (facultatif)',
            'class' => 'form-control'
        ]
    ])
            ->add('address', TextType::class, [
        'label' => 'Adresse?',
        'attr' => [
            'placeholder' => '9 rue, André Darbon ...',
            'class' => 'form-control'
        ]
    ])
            ->add('postal', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un code postal',
                    ]),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Veuillez entrer un code postal valide (5 chiffres)',
                    ]),
                ],
        'label' => 'Code postal',
        'attr' => [
            'placeholder' => 'Votre code postal',
            'class' => 'form-control'
        ]
    ])
            ->add('city', TextType::class, [
        'label' => 'Votre ville',
        'attr' => [
            'placeholder' => 'Ville',
            'class' => 'form-control'
        ]
    ])
            ->add('country', CountryType::class, [
        'label' => 'Pays',
        'attr' => [
            'placeholder' => 'Votre pays',
            'class' => 'form-control'
        ]
    ])
            ->add('phone', TelType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un numéro de téléphone',
                    ]),

                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Veuillez entrer un numéro de téléphone valide (chiffres uniquement)',
                    ]),
                ],
                          'label' => 'Numéro de téléphone',
                           'attr' => [
                    'placeholder' => 'Votre numéro de téléphone',
                          'class' => 'form-control'
        ]
    ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-success btn-md mt-3'
                ]

            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
