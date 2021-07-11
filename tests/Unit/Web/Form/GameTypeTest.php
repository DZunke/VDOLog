<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Web\Form;

use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use VDOLog\Core\Domain\Game;
use VDOLog\Web\Form\GameType;

final class GameTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = ['name' => 'test game'];
        $game     = new Game();

        $form = $this->factory->create(GameType::class, $game);
        $form->submit($formData);

        self::assertSame($game, $game);
        self::assertSame('test game', $game->getName());
    }

    public function testSubmitInvalidData(): void
    {
        $formData = ['name' => ''];
        $game     = new Game();

        $form = $this->factory->create(GameType::class, $game);
        $form->submit($formData);

        self::assertFalse($form->isValid());
        self::assertSame($game, $game);

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
