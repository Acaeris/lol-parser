<?php

namespace LeagueOfData\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use LeagueOfData\Repository\Realm\JsonRealmRepository;
use LeagueOfData\Repository\Realm\SqlRealmRepository;

class RealmUpdateCommand extends Command
{
    /**
     * @var LoggerInterface Logger
     */
    private $logger;

    /**
     * @var FetchServiceInterface API Service
     */
    private $apiRepository;

    /**
     * @var StoreServiceInterface DB Service
     */
    private $dbRepository;

    public function __construct(
        LoggerInterface $logger,
        JsonRealmRepository $apiRepository,
        SqlRealmRepository $dbRepository
    ) {
        parent::__construct();
        $this->logger = $logger;
        $this->apiRepository = $apiRepository;
        $this->dbRepository = $dbRepository;
    }

    /**
     * Configures the Realm update command
     */
    protected function configure()
    {
        $this->setName('update:realm')
            ->setDescription('API processor command for Data Dragon realm data');
    }

    /**
     * Executes the update, fetching all realms at the same time
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('Updating Realm data');
        $this->dbRepository->add($this->apiRepository->fetch([]));
        $this->dbRepository->store();
        $this->logger->info('Command complete');
    }
}
