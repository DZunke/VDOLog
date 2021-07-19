<?php

namespace VDOLog\Web\Form\Game;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VDOLog\Core\Domain\Game\TimeFrame;

final class TimeFrameType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => TimeFrame::class]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

    }
}
