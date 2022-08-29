<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Game;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

final class ImportAccessScanPointsType extends AbstractType
{
    public const EXCEL_MIME_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

    /** @inheritDoc */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'csv',
            FileType::class,
            [
                'mapped' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'text/csv',
                            self::EXCEL_MIME_TYPE,
                        ],
                    ]),
                ],
            ],
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label_format' => 'game.import_statistic.%name%',
            'translation_domain' => 'forms',
        ]);
    }
}
