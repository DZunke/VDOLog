<?php

declare(strict_types=1);

namespace VDOLog\Web\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use VDOLog\Core\Application\User\CreateUser;
use VDOLog\Core\Domain\Common\EMail;

use function assert;
use function count;

final class InitializeDatabase extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly Connection $connection,
        private readonly MessageBusInterface $bus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('vdo:initialize-application');
        $this->setDescription('Initialized the application database');
        $this->addOption(
            'force',
            'f',
            InputOption::VALUE_NONE,
            'Will force the creation by deleting an existing database first',
        );
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Database Initialization');

        if ($this->isDatabaseInitialized() && $input->getOption('force') === false) {
            $this->io->warning('Database Already Initialized');

            return self::INVALID;
        }

        if ($input->getOption('force') === true) {
            $this->clearExistingSchema();
        }

        $this->createSchema();
        $this->initializeAdminUser();

        $this->io->success('Initialization Finished Successfully');

        return self::SUCCESS;
    }

    private function isDatabaseInitialized(): bool
    {
        $existingTables = $this->connection->createSchemaManager()->listTableNames();

        return count($existingTables) > 0;
    }

    private function createSchema(): void
    {
        $input       = new ArrayInput(['command' => 'doctrine:schema:create', '-q']);
        $application = $this->getApplication();
        assert($application instanceof Application);

        $application->setAutoExit(false);
        $application->run($input, new NullOutput());

        $this->io->comment('Schema created');
    }

    private function clearExistingSchema(): void
    {
        $input       = new ArrayInput(['command' => 'doctrine:schema:drop', '--force' => true, '-q' => true, '--full-database' => true]);
        $application = $this->getApplication();
        assert($application instanceof Application);

        $application->setAutoExit(false);
        $application->run($input, new NullOutput());

        $this->io->comment('Existing Schema dropped');
    }

    private function initializeAdminUser(): void
    {
        $message = new CreateUser(
            new EMail($_ENV['APP_DEFAULT_USER']),
            $_ENV['APP_DEFAULT_PASSWORD'],
            'Administrator',
        );
        $message->asAdmin();

        $this->bus->dispatch($message);
    }
}
