<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Game;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use VDOLog\Web\Form\Dto\Game\TimeFrameDto;
use VDOLog\Web\Validator\DateTimeRelativeString;

final class TimeFrameType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TimeFrameDto::class,
        ]);
    }

    /** @inheritDoc */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'eventStartsAt',
            DateTimeType::class,
            [
                'label' => 'Beginn Arbeitstag',
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotNull(),
                ],
            ]
        );

        $builder->add(
            'optSpectatorEntry',
            TextType::class,
            [
                'label' => 'Beginn Einlassphase',
                'help' => 'Relativ zum Beginn des Arbeitstages',
                'constraints' => [
                    new NotBlank(),
                    new DateTimeRelativeString(),
                ],
            ]
        );

        $builder->add(
            'optEventActBegin',
            TextType::class,
            [
                'label' => 'Veranstaltungsbeginn',
                'help' => 'Relativ zum Beginn des Arbeitstages',
                'constraints' => [
                    new NotBlank(),
                    new DateTimeRelativeString(),
                ],
            ]
        );

        $builder->add(
            'optEventActEnd',
            TextType::class,
            [
                'label' => 'Veranstaltungsende',
                'help' => 'Relativ zum Beginn des Arbeitstages',
                'constraints' => [
                    new NotBlank(),
                    new DateTimeRelativeString(),
                ],
            ]
        );
    }
}
