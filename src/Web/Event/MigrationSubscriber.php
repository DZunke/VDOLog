<?php

declare(strict_types=1);

namespace VDOLog\Web\Event;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

use function count;

final class MigrationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private KernelInterface $kernel,
        private Connection $connection
    ) {
    }

    /**
     * @return array<string, array<int|string, array<int|string, int|string>|int|string>|string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['databaseInitialization', 10],
            ],
        ];
    }

    public function databaseInitialization(RequestEvent $event): void
    {
        $existingTables = $this->connection->createSchemaManager()->listTableNames();
        if (count($existingTables) > 0) {
            return;
        }

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(['command' => 'doctrine:schema:create']);
        $application->run($input, new NullOutput());
    }
}
