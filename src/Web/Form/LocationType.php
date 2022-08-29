<?php

declare(strict_types=1);

namespace VDOLog\Web\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Tienvx\UX\CollectionJs\Form\CollectionJsType;
use VDOLog\Core\Domain\Location;
use VDOLog\Web\Form\Dto\LocationDto;
use VDOLog\Web\Form\Location\AccessScannerType;
use VDOLog\Web\Validator\UniqueEntity;

final class LocationType extends AbstractType
{
    /** @inheritDoc */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'empty_data' => '',
                'constraints' => [
                    new NotBlank(),
                    new UniqueEntity([
                        'entityClass' => Location::class,
                        'field' => 'name',
                        'ignoreEntryIdField' => 'id',
                    ]),
                ],
            ],
        );

        $builder->add(
            'accessScanners',
            CollectionJsType::class,
            [
                'entry_type' => AccessScannerType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'call_post_add_on_init' => true,
            ],
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LocationDto::class,
            'label_format' => 'location.%name%',
            'translation_domain' => 'forms',
        ]);
    }
}
