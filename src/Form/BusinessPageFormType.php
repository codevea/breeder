<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Activity;
use App\Entity\BusinessPage;
use App\Entity\Siret;
use App\Form\AddressFormType;
use App\Form\SiretFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @extends AbstractType<BusinessPage>
 */
class BusinessPageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        /**
         * Install DynamicFormBuilder:.
         *
         *    composer require symfonycasts/dynamic-forms
         */
        $builder = new DynamicFormBuilder($builder);

        $builder->add('activity', EntityType::class, [
            'class' => Activity::class,
            'choice_label' => 'name',
            'label' => false,
            'expanded' => true,
            'multiple' => false,
            'constraints' => [new Assert\Valid()],
        ]);

        $builder->addDependent('addSiret', ['activity'], function (DependentField $field, ?string $activity) {
            if ($activity === 'Éleveur de chat' || $activity === 'Éleveur de chien') {
                $field->add(ChoiceType::class, [
                    'choices' => ['Oui' => '1', 'Utiliser un numéro de SIRET que j\'ai déjà enregistré' => '2'],
                    'placeholder' => 'Sélectionnez ...',
                    'label' => 'Voulez-vous ajouter un nouveau numéro de SIRET ?',
                    'mapped' => false,
                    'row_attr' => ['class' => 'formColumn'],
                ]);
            }
        });

        $builder->addDependent('siret', ['addSiret'], function (DependentField $field, ?string $addSiret) {

            if ($addSiret === '1') {
                // AJOUT NOUVEAU : On utilise le formulaire de SiretFormType
                $field->add(SiretFormType::class, [
                    'label' => false,
                    'constraints' => [new Assert\Valid()],
                ]);
            } elseif ($addSiret === '2') {
                // SÉLECTION EXISTANT : On pioche dans la base de données pour récuperer le ou les numéros de SIRET (enregistrer en string)
                $field->add(EntityType::class, [
                    'class' => Siret::class,
                    'choice_label' => 'number',
                    'placeholder' => '-- Choisir votre numéro de SIRET --',
                    'label' => 'Séléctionnez le SIRET',
                    'required' => false,
                    'row_attr' => ['class' => 'formColumn'],
                ]);
            }
        });

        $builder->addDependent('address', ['siret'], function (DependentField $field, ?string $siret) {
            if ($siret !== null) {
                $field->add(AddressFormType::class, [
                    'label' => false,
                    'row_attr' => ['class' => 'formColumn'],
                ]);
            }
        });

        $builder->addDependent('submit', ['siret'], function (DependentField $field, ?string $siret) {
            if ($siret !== null) {
                $field->add(SubmitType::class, [
                    'label' => 'Enregistrer',
                    'row_attr' => ['class' => 'formColumn'],
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BusinessPage::class,
        ]);
    }
}
