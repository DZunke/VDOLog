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
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /** @inheritDoc */
    public function findUnsentRemindersSince(DateTimeImmutable $lastCheck): iterable
    {
        $qb = $this->em->createQueryBuilder();
        $qb->from(Reminder::class, 'r');
        $qb->select('r');
        $qb->where($qb->expr()->isNull('r.sentAt'));

        $reminder = new ArrayCollection($qb->getQuery()->getResult());
        $reminder = $reminder->filter(static function (Reminder $reminder) use ($lastCheck): bool {
            return $reminder->getRemindAtAsDate() <= $lastCheck;
        });

        return $reminder;
    }
}
