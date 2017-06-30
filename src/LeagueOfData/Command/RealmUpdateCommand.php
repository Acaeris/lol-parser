<?php

namespace LeagueOfData\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use LeagueOfData\Service\Json\Realm\RealmCollection as ApiCollection;
use LeagueOfData\Service\Sql\Realm\RealmCollection as DbCollection;

class RealmUpdateCommand extends Command
{
    /**
     * @var LoggerInterface Logger
     */
    private $logger;

    /**
     * @var FetchServiceInterface API Service
     */
    private $apiAdapter;

    /**
     * @var StoreServiceInterface DB Service
     */
    private $dbAdapter;

    public function __construct(
        LoggerInterface $logger,
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter
    ) {
        parent::__construct();
        $this->logger = $logger;
        $this->apiAdapter = $apiAdapter;
        $this->dbAdapter = $dbAdapter;
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
        $this->dbAdapter->add($this->apiAdapter->fetch([]));
        $this->dbAdapter->store();
        $this->logger->info('Command complete');
    }
}
