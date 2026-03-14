<?php

namespace App\Form;

use App\Entity\Breeder;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\BlockQuoteField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\BoldField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\CleanField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\EmojiField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\ItalicField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\StrikeField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\UnderlineField;
use Ehyiah\QuillJsBundle\DTO\QuillGroup;
use Ehyiah\QuillJsBundle\Form\QuillType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class BreederPresentationFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('presentation', QuillType::class,  [
            'label' => false,
            'quill_options' => [
                QuillGroup::build(
                    new BoldField(),
                    new ItalicField(),
                    new UnderlineField(),
                    new StrikeField(),
                    new BlockQuoteField(),
                    new CleanField(),
                    new EmojiField(),
                ),
            ],
            'quill_extra_options' => [
                'theme' => 'snow',
            ],
            'row_attr' => ['class' => 'text'],
        ]);

        $builder->add('submit', SubmitType::class, [
        'label' => 'Enregistrer'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Breeder::class,
            'validation_groups' => ['presentation_update'],
        ]);
    }
}
