<?php

declare(strict_types=1);

namespace VDOLog\Web\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use VDOLog\Core\Domain\Location;
use VDOLog\Web\Form\Dto\CreateGameDto;
use VDOLog\Web\Form\Game\TimeFrameType;

class CreateGameType extends AbstractType
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
                ],
            ]
        );

        $builder->add(
            'location',
            EntityType::class,
            [
                'class' => Location::class,
                'choice_label' => 'name',
                'placeholder' => 'game.select.location',
            ]
        );

        $builder->add('timeFrame', TimeFrameType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateGameDto::class,
            'label_format' => 'game.%name%',
            'translation_domain' => 'forms',
        ]);
    }
}
