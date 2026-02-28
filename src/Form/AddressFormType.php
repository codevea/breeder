<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('street', TextType::class, [
            'label' => 'Adresse rue',
            'row_attr' => ['class' => 'formColumn'],
        ]);

        $builder->add('zipCode', TextType::class, [
            'label' => 'Code postal',
            'row_attr' => ['class' => 'formColumn'],
        ]);

        $builder->add('city', TextType::class, [
            'label' => 'Ville',
            'row_attr' => ['class' => 'formColumn'],
        ]);

        $builder->add('country', ChoiceType::class, [
            'label' => 'Pays',
            'row_attr' => ['class' => 'formColumn'],
            'choices' => [
                'France' => 'FR',
                //  Sera mis en place d'autres pays par la suite
                // 'Belgique' => 'BE',
            ],
            'placeholder' => '-- Choisir un pays --',
        ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
