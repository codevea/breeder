<?php

namespace App\Form;

use App\Entity\Affixe;
use App\Entity\Breeder;
use App\Entity\RaceCat;
use App\Entity\RaceDog;
use App\Entity\WebSite;
use App\Form\WebSiteFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;


/**
 * @extends AbstractType<Breeder>
 */
class BreederFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $type = $options['breeder_type']; // 'cat' ou 'dog'

        /**
         * Install DynamicFormBuilder:.
         *
         *    composer require symfonycasts/dynamic-forms
         */
        $builder = new DynamicFormBuilder($builder);


        $builder->add('addAffixe', ChoiceType::class, [
            'choices' => ['Oui' => '1', 'Non' => '2'],
            'placeholder' => 'Choisissez...',
            'label' => 'Ajouter un nouvel affixe pour votre élevage ?',
            'mapped' => false,
            'row_attr' => ['class' => 'formColumn'],
        ]);


        $builder->addDependent('affixe', ['addAffixe'], function (DependentField $field, ?string $addAffixe) use ($type) {

            if ($addAffixe === '1') {
                $field->add(AffixeFormType::class, [
                    'label' => false,
                    'species' => $type === 'cat' ? 'chat' : 'chien',
                    'constraints' => [new Assert\Valid()],
                    'validation_groups' => [$type === 'cat' ? 'eleveur-de-chat' : 'eleveur-de-chien'],
                ]);
            } elseif ($addAffixe === '2') {
                $field->add(EntityType::class, [
                    'class' => Affixe::class,
                    'choice_label' => 'name',
                    'placeholder' => '-- Sélectionnez un affixe --',
                    'label' => 'Choisir un affixe existant',
                    'required' => false,
                    'row_attr' => ['class' => 'formColumn'],
                    'validation_groups' => [$type === 'cat' ? 'eleveur-de-chat' : 'eleveur-de-chien'],
                ]);
            }
        });


        $builder->addDependent($type === 'cat' ? 'raceCat' : 'raceDog', ['affixe'], function (DependentField $field, ?string $affixe) use ($type) {

            if ($affixe) {
                $field->add(EntityType::class, [
                    'class' => $type === 'cat' ? RaceCat::class : RaceDog::class,
                    'choice_label' => 'race',
                    'placeholder' => '-- Choisir la race --',
                    'label' => 'Choisissez la race élevée',
                    'validation_groups' => [$type === 'cat' ? 'eleveur-de-chat' : 'eleveur-de-chien'],
                    'row_attr' => ['class' => 'formColumn'],
                ]);
            }
        });


        // 1. On définit dynamiquement le nom du champ de race à surveiller
        $raceFieldName = ($type === 'cat') ? 'raceCat' : 'raceDog';

        $builder->addDependent('addWebSite', [$raceFieldName], function (DependentField $field, ?string $raceValue) {
            // Si une race est sélectionnée ($raceValue n'est pas null ou vide)
            if ($raceValue) {
                $field->add(ChoiceType::class, [
                    'choices' => ['Oui' => '1', 'Non' => '2'],
                    'placeholder' => 'Choisissez...',
                    'label' => 'Voulez-vous enregistrer l\'adresse de votre site internet ?',
                    'mapped' => false,
                    'row_attr' => ['class' => 'formColumn'],
                ]);
            }
        });


        $builder->addDependent('webSite', ['addWebSite'], function (DependentField $field, ?string $addWebSite) {

            if ($addWebSite === '1') {
                $field->add(WebSiteFormType::class, [
                    'label' => false,
                    'validation_groups' => ['eleveur-de-chat', 'eleveur-de-chien'],
                    'constraints' => [new Assert\Valid()],
                ]);
            }
        });

        $builder->addDependent('addSelectWebSite', ['addWebSite'], function (DependentField $field, ?string $addWebSite) {

            if ($addWebSite === '2') {
                $field->add(ChoiceType::class, [
                    'choices' => ['Oui' => '1', 'Non' => '2'],
                    'placeholder' => 'Choisissez...',
                    'label' => 'Voulez-vous selectionnez votre site parmie ceux que vous avez déjà enregistrer ?',
                    'mapped' => false,
                    'row_attr' => ['class' => 'formColumn'],
                ]);
            }
        });


        $builder->addDependent('webSite', ['addSelectWebSite'], function (DependentField $field, ?string $addSelectWebSite) {

            if ($addSelectWebSite === '1') {
                $field->add(EntityType::class, [
                    'class' => WebSite::class,
                    'choice_label' => 'url',
                    'placeholder' => '-- Sélectionnez l\'adresse de votre site web --',
                    'label' => 'Choisir une adresse URL existante ?',
                    'required' => false,
                    'row_attr' => ['class' => 'formColumn'],
                    'validation_groups' => ['eleveur-de-chat', 'eleveur-de-chien'],
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Breeder::class,
            'breeder_type' => 'cat', // valeur par défaut
        ]);
    }
}
