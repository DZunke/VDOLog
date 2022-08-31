<?php

declare(strict_types=1);

namespace VDOLog\Core\Infrastructure\Doctrine;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use VDOLog\Core\Domain\Game\Reminder;
use VDOLog\Core\Domain\Game\ReminderRepository;

class DoctrineReminderRepository implements ReminderRepository
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /** @inheritDoc */
    public function findUnsentRemindersSince(DateTimeImmutable $lastCheck): iterable
    {
        $qb = $this->em->createQueryBuilder();
        $qb->from(Reminder::class, 'r');
        $qb->select('r');
        $qb->where($qb->expr()->isNull('r.sentAt'));

        $reminder = new ArrayCollection($qb->getQuery()->getResult());

        return $reminder->filter(static function (Reminder $reminder) use ($lastCheck): bool {
            return $reminder->getRemindAtAsDate() <= $lastCheck;
        });
    }
}
