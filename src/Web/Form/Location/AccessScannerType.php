<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Location;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use VDOLog\Web\Form\Dto\Location\AccessScannerDto;

final class AccessScannerType extends AbstractType
{
    /** @inheritDoc */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'id',
            HiddenType::class,
            ['empty_data' => '']
        );

        $builder->add(
            'name',
            TextType::class,
            [
                'empty_data' => '',
                'constraints' => [new NotBlank()],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AccessScannerDto::class,
            'label_format' => 'location.access_scanner.%name%',
            'translation_domain' => 'forms',
        ]);
    }
}
