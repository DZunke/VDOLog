<?php

declare(strict_types=1);

namespace VDOLog\Web\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function assert;
use function class_exists;
use function count;
use function is_string;

final class UniqueEntityValidator extends ConstraintValidator
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /** @inheritDoc */
    public function validate($value, Constraint $constraint): void
    {
        if (! $constraint instanceof UniqueEntity) {
            throw new UnexpectedTypeException($constraint, UniqueEntity::class);
        }

        if (! is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $entityClass = $constraint->entityClass;
        assert(class_exists($entityClass));

        $repository = $this->em->getRepository($entityClass);

        $result = $repository->findBy([$constraint->field => $value]);
        if (count($result) === 0) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->setInvalidValue($value)
            ->setCode(UniqueEntity::NOT_UNIQUE_ERROR)
            ->setCause($result)
            ->addViolation();
    }
}
