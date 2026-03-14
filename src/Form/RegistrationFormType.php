<?php

namespace App\Form;

use App\Entity\User;
use App\Form\BusinessPageFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;


class RegistrationFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'row_attr' => ['class' => 'formColumn']
            ])

            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'row_attr' => ['class' => 'formColumn'],
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir un mot de passe.'),
                    new Length(
                        min: 14,
                        minMessage: 'Votre mot de passe doit contenir au moins {{ limit }} caractères. ',
                        max: 50
                    ),
                ],
            ])

            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Madame' => 'Madame',
                    'Monsieur' => 'Monsieur',
                ],
                'label' => 'Dénominateur',
                'row_attr' => ['class' => 'formColumn'],
            ])


            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'row_attr' => ['class' => 'formColumn']
            ])

            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'row_attr' => ['class' => 'formColumn']
            ])

            ->add('siren', TextType::class, [
                'label' => 'Siren',
                'row_attr' => ['class' => 'formColumn']
            ])

            ->add('businessPage', CollectionType::class, [
                'entry_type'    => BusinessPageFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'entry_options' => ['label' => false],
                'constraints' => [new Assert\Valid()],
                'row_attr' => ['class' => 'collection'],
                'attr' => [
                    'data-controller' => 'form-collection'
                ]
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Je confirme accepter les conditions d’utilisation du site.',
                'row_attr' => ['class' => 'checkbox'],
                'constraints' => [
                    new IsTrue(message: 'Vous devez accepter nos conditions d’utilisation.'),
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Créer un compte',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
