<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Ce code définit un formulaire Symfony appelé RegisterType
 * qui est associé à l'entité User.
 * Le formulaire contient des champs pour le prénom, le nom, l'e-mail et le mot de passe.
 * Les types de champ utilisés sont fournis par le composant Form de Symfony.
 * Chaque champ a une étiquette label et un attribut de placeholder pour améliorer l'expérience utilisateur.
 * Le champ de mot de passe est de type RepeatedType, ce qui signifie qu'il est répété pour confirmation.
 * Enfin, il y a un champ de type SubmitType pour le bouton d'inscription.
 * Les options du formulaire, notamment la classe des données, sont configurées via la méthode configureOptions.
 */
class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout des differents champs et leurs types au formulaire
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',

                'attr'=>['placeholder' => 'Prénom']
        ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',

                'attr'=>['placeholder' => 'Nom']
        ])
            ->add('email', EmailType::class, [
                'label' => 'Email',

                'attr'=>['placeholder' => 'Email']
        ])
            ->add('password', RepeatedType::class, [
                'label' => 'Mot de passe',
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'invalid_message' => 'Les mots de passe ne correspondent pas'
        ])

            ->add('submit', SubmitType ::class, [
            'label' => "S'inscrire"
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure les options du formulaire, indiquant que la classe associée aux données est User::class
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
