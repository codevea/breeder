<?php

namespace App\Form;


use App\Entity\Affixe;
use App\Entity\Pet;
use App\Entity\PetGender;
use App\Form\IcadFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'animal',
                'row_attr' => ['class' => 'formColumn'],
            ])

            // ->add('affixe', AffixeFormType::class, [
            //     'label' => false,
            //     'row_attr' => ['class' => 'formColumn'],
            // ])

            ->add('affixe', EntityType::class, [
                'class' => Affixe::class,
                'choice_label' => 'name',
                'placeholder' => '-- Choisir l\'affixe --',
                'label' => 'Séléctionnez l\'affixe',
                'required' => false,
                'row_attr' => ['class' => 'formColumn'],
            ])

            ->add('icad', IcadFormType::class, [
                'label' => false,
                'row_attr' => ['class' => 'formColumn'],
            ])

            ->add('petGender', EntityType::class, [
                'class' => PetGender::class,
                'choice_label' => 'gender',
                'placeholder' => '-- Choisir --',
                'label' => 'Séléctionnez le sexe',
                'required' => false,
                'row_attr' => ['class' => 'formColumn'],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'row_attr' => ['class' => 'formColumn'],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pet::class,
        ]);
    }
}
