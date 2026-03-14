<?php
namespace App\Form;

use App\Entity\Affixe;
use App\Entity\AffixeRegistration;
use App\Repository\AffixeRegistrationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AffixeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $species = $options['species']; // 'chat' ou 'chien'

        $builder
            ->add('name', TextType::class, [
                'label' => 'Affixe',
                'row_attr' => ['class' => 'formColumn'],
            ])

            ->add('affixeRegistration', EntityType::class, [
                'class' => AffixeRegistration::class,
                'choice_label' => 'officialRegister',
                'label' => 'Livre d\'enregistrement',
                'placeholder' => '-- Choisir le livre --',
                'required' => false,
                'row_attr' => ['class' => 'formColumn'],
                'constraints' => [new Assert\Valid()],
                'query_builder' => function (AffixeRegistrationRepository $repo) use ($species) {
                    return $repo->createQueryBuilder('a')
                        ->andWhere('a.species = :species')
                        ->setParameter('species', $species)
                        ->orderBy('a.officialRegister', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affixe::class,
            'species' => 'chat', // valeur par défaut
        ]);
    }
}