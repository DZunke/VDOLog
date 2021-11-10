<?php

declare(strict_types=1);

namespace VDOLog\Web\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use VDOLog\Web\Form\Dto\ChangePasswordDto;

final class ChangePasswordType extends AbstractType
{
    /** @inheritDoc */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'password',
            RepeatedType::class,
            [
                'empty_data' => '',
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank(),
                    new NotCompromisedPassword(),
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChangePasswordDto::class,
            'label_format' => 'user.%name%',
            'translation_domain' => 'forms',
        ]);
    }
}
