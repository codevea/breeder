<?php

namespace App\Form;


use App\Entity\Affixe;
use App\Entity\Pet;
use App\Entity\PetGender;
use App\Form\IcadFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

/**
 * @extends AbstractType<Pet>
 */
class PetFormType extends AbstractType
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

        $builder->add('petGender', EntityType::class, [
            'class' => PetGender::class,
            'choice_label' => 'gender',
            'placeholder' => '-- Choisir --',
            'label' => 'Séléctionnez le sexe',
            'required' => false,
            'row_attr' => ['class' => 'formColumn'],
        ]);

        $builder->add('name', TextType::class, [
            'label' => 'Nom de l\'animal',
            'row_attr' => ['class' => 'formColumn'],
        ]);

        $builder->add('icad', IcadFormType::class, [
            'label' => false,
            'row_attr' => ['class' => 'formColumn'],
            'constraints' => [new Assert\Valid()],
        ]);

        $builder->add('addAffixe', ChoiceType::class, [
            'choices' => ['Oui' => '1', 'Non' => '2'],
            'placeholder' => 'Choisissez...',
            'label' => 'Ajouter un nouvel affixe pour cette race ?',
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pet::class,
            'breeder_type' => 'cat', // valeur par défaut
                //   'csrf_protection' => true,
        ]);
    }
}
