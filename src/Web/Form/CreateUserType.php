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
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use VDOLog\Core\Domain\User;
use VDOLog\Web\Form\Dto\CreateUserDto;
use VDOLog\Web\Validator\UniqueEntity;

class CreateUserType extends AbstractType
{
    /** @inheritDoc */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'email',
            TextType::class,
            [
                'empty_data' => '',
                'constraints' => [
                    new NotBlank(),
                    new Email(['mode' => Email::VALIDATION_MODE_STRICT]),
                    new UniqueEntity([
                        'entityClass' => User::class,
                        'field' => 'email',
                    ]),
                ],
            ]
        );

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

        $builder->add(
            'displayName',
            TextType::class,
            ['required' => false]
        );

        $builder->add(
            'isAdmin',
            CheckboxType::class,
            ['required' => false]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateUserDto::class,
            'label_format' => 'user.%name%',
            'translation_domain' => 'forms',
        ]);
    }
}
