<?php

namespace App\Form;

use App\Entity\Affixe;
use App\Entity\Breeder;
use App\Entity\RaceCat;
use App\Entity\RaceDog;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
// use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

        // 1. Choix initial : Nouveau ou Existant ?
        $builder->add('addAffixe', ChoiceType::class, [
            'choices' => ['Oui' => '1', 'Non' => '2'],
            'placeholder' => 'Choisissez...',
            'label' => 'Ajouter un nouvel affixe pour cette race ?',
            'mapped' => false,
            'row_attr' => ['class' => 'formColumn'],
        ]);

        // 2. Champ affixe dépendant
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

        // 3. Champ race dépendant
        $builder->addDependent($type === 'cat' ? 'raceCat' : 'raceDog', ['addAffixe'], function (DependentField $field, ?string $addAffixe) use ($type) {

            if ($addAffixe !== null) {
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

        // $builder->add('submit', SubmitType::class, [
        //     'label' => 'Ajouter',
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Breeder::class,
            'breeder_type' => 'cat', // valeur par défaut
            'csrf_protection' => false,
        ]);
    }
}
