<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Web\Form;

use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use VDOLog\Web\Form\CreateGameType;
use VDOLog\Web\Form\Dto\CreateGameDto;

final class CreateGameTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = ['name' => 'test game'];
        $obj      = new CreateGameDto();

        $form = $this->factory->create(CreateGameType::class, $obj);
        $form->submit($formData);

        self::assertSame($obj, $obj);
        self::assertSame('test game', $obj->name);
    }

    public function testSubmitInvalidData(): void
    {
        $formData = ['name' => ''];
        $obj      = new CreateGameDto();

        $form = $this->factory->create(CreateGameType::class, $obj);
        $form->submit($formData);

        self::assertFalse($form->isValid());
        self::assertSame($obj, $obj);

        self::assertCount(1, $form->get('name')->getErrors());
    }

    /** @inheritDoc */
    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }
}
