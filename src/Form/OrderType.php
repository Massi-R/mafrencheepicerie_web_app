<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      $user = $options['user'];
        $builder
            ->add('addresses', EntityType::class, [
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                'label' => 'Choisissez votre address de livraison',
                'multiple' => false,
                'expanded' => true,
                'required' => true,
            ])
            ->add('carriers', EntityType::class, [
                'class' => Carrier::class,
                'label' => 'Choisissez votre transporteur',
                'multiple' => false,
                'expanded' => true,
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider mon paiment',
                'attr' => ['class' => 'btn btn-success'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => array()
        ]);
    }
}
