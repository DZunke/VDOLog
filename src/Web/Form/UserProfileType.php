<?php

declare(strict_types=1);

namespace VDOLog\Web\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use VDOLog\Web\Form\Dto\UserProfileDto;

final class UserProfileType extends AbstractType
{
    /** @inheritDoc */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'displayName',
            TextType::class,
            ['empty_data' => '', 'required' => false],
        );

        $builder->add(
            'enableNotifications',
            CheckboxType::class,
            ['required' => false],
        );

        $builder->add(
            'changePassword',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'constraints' => [
                    new NotCompromisedPassword(),
                ],
            ],
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfileDto::class,
            'label_format' => 'user.%name%',
            'translation_domain' => 'forms',
        ]);
    }
}
