<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Game;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use VDOLog\Web\Form\Dto\Game\NewReminderDto;
use VDOLog\Web\Validator\DateTimeRelativeString;

final class NewReminderType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => NewReminderDto::class]);
    }

    /** @inheritDoc */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => 'Titel',
                'empty_data' => '',
                'constraints' => [new NotBlank()],
            ]
        );

        $builder->add(
            'message',
            TextareaType::class,
            [
                'label' => 'Nachricht',
                'empty_data' => '',
                'constraints' => [new NotBlank()],
            ]
        );

        $builder->add(
            'remindAt',
            TextType::class,
            [
                'label' => 'Zeit',
                'help' => 'Relativ zum Veranstaltungsbeginn',
                'empty_data' => '',
                'constraints' => [new NotBlank(), new DateTimeRelativeString()],
            ]
        );
    }
}
