<?php

declare(strict_types=1);

namespace VDOLog\Web\Validator;

use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function assert;
use function class_exists;
use function count;
use function is_object;
use function is_string;
use function strlen;

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

        $qb = $this->em->createQueryBuilder();
        $qb->from($entityClass, 'e');
        $qb->select('e');
        $qb->andWhere($qb->expr()->eq('e.' . $constraint->field, $qb->expr()->literal($value)));

        if (strlen($constraint->ignoreEntryIdField) > 0) {
            $idField = $constraint->ignoreEntryIdField;
            $objData = $this->context->getRoot()->getNormData();
            assert(is_object($objData));

            $reflection = new ReflectionClass($objData::class);
            if ($reflection->hasProperty($idField)) {
                $property = $reflection->getProperty($idField);
                $property->setAccessible(true);
                $givenId = $property->getValue($objData);

                if (strlen($givenId) > 0) {
                    $qb->andWhere($qb->expr()->neq('e.id', $qb->expr()->literal($givenId)));
                }
            }
        }

        $result = $qb->getQuery()->getResult();
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
