<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ResetPasswordRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'row_attr' => ['class' => 'formColumn'],
                'attr' => ['autocomplete' => 'email'],
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir votre adresse e-mail',
                    ),
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer l\'e-mail de réinitialisation',
            ])    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
