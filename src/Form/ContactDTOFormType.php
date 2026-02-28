<?php

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactDTOFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Votre nom',
                'empty_data' => '',
                'row_attr' => ['class' => 'formColumn'],
            ])

            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'empty_data' => '',
                'row_attr' => ['class' => 'formColumn']
            ])

            ->add('content', TextareaType::class, [
                'label' => 'Votre message',
                'empty_data' => '',
                'row_attr' => ['class' => 'formColumn']
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Je confirme accepter les conditions d’utilisation du site.',
                'row_attr' => ['class' => 'formCheckbox'],
                'constraints' => [
                    new IsTrue(message: 'Vous devez accepter nos conditions d’utilisation.'),
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'row_attr' => ['class' => 'formColumn'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
