<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Address;
use App\Entity\BusinessPage;
use App\Entity\Siret;
use App\Entity\User;
use App\Form\AddressFormType;
use App\Form\PhoneFormType;
use App\Form\SiretFormType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;


/**
 * @extends AbstractType<BusinessPage>
 */
class BusinessPageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // On récupère l'utilisateur passé en option
        $user = $options['user'];
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

        $builder->addDependent('siret', ['addSiret'], function (DependentField $field, ?string $addSiret) use ($user) {

            if ($addSiret === '1') {
                // AJOUT NOUVEAU
                $field->add(SiretFormType::class, [
                    'label' => false,
                    'constraints' => [new Assert\Valid()],
                ]);
            } elseif ($addSiret === '2') {
                // SÉLECTION EXISTANT
                $field->add(EntityType::class, [
                    'class' => Siret::class,
                    'choice_label' => 'number',
                    'placeholder' => '-- Choisir votre numéro de SIRET --',
                    'label' => 'Sélectionnez le SIRET',
                    'required' => false,
                    'row_attr' => ['class' => 'formColumn'],

                    // FILTRE ICI :
                    'query_builder' => function (EntityRepository $er) use ($user) {
                        return $er->createQueryBuilder('s')
                            ->where('s.user = :user')
                            ->setParameter('user', $user)
                            ->orderBy('s.number', 'ASC');
                    },
                ]);
            }
        });


        $builder->addDependent('addAddress', ['siret'], function (DependentField $field, ?string $siret) {
            if ($siret !== null) {
                $field->add(ChoiceType::class, [
                    'choices' => ['Oui' => '1', 'Utiliser une adresse déjà enregistré' => '2'],
                    'placeholder' => 'Sélectionnez ...',
                    'label' => 'Voulez-vous ajouter une nouvelle adresse ?',
                    'mapped' => false,
                    'row_attr' => ['class' => 'formColumn'],
                ]);
            }
        });



        $builder->addDependent('address', ['addAddress'], function (DependentField $field, ?string $addAddress) use ($user) {
            if ($addAddress === '1') {
                $field->add(AddressFormType::class, [
                    'label' => false,
                    'constraints' => [new Assert\Valid()],
                ]);
            } elseif ($addAddress === '2') {
                $field->add(EntityType::class, [
                    'class' => Address::class,
                    'placeholder' => '-- Choisir votre adresse --',
                    // Ajout du filtre par utilisateur ici :
                    // $er est l'instance du Repository de l'entité (ex: AddressRepository)
                    'query_builder' => function (EntityRepository $er) use ($user) {
                        // 1. On crée une requête personnalisée sur l'alias 'a' (pour adresse)
                        return $er->createQueryBuilder('a')
                            // 2. On ajoute une condition : la colonne 'user' de l'adresse 
                            // doit être égale au paramètre nommé ':user'
                            ->where('a.user = :user')
                            // 3. On sécurise la requête en injectant la variable $user 
                            // dans le paramètre ':user' (protection contre les injections SQL)
                            ->setParameter('user', $user)
                            // 4. (Optionnel) On peut trier les résultats
                            ->orderBy('a.city', 'ASC');
                    },
                    'label' => 'Sélectionnez votre adresse',
                    'required' => false,
                    'row_attr' => ['class' => 'formColumn'],
                ]);
            }
        });

        $builder->addDependent('phone', ['siret'], function (DependentField $field, ?string $siret) {
            if ($siret !== null) {
                $field->add(CollectionType::class, [
                    'entry_type' => PhoneFormType::class,
                    'label' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'prototype' => true,
                    'constraints' => [new Assert\Valid()],
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BusinessPage::class,
            'user' => null, // On définit l'option par défaut
        ]);

        // On peut forcer le type pour plus de sécurité
        $resolver->setAllowedTypes('user', [User::class, 'null']);
    }
}
