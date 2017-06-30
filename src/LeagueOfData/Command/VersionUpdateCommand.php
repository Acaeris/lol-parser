<?php

namespace LeagueOfData\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Psr\Log\LoggerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use LeagueOfData\Service\Json\Version\VersionCollection as ApiCollection;
use LeagueOfData\Service\Sql\Version\VersionCollection as DbCollection;

class VersionUpdateCommand extends Command
{

    /**
     * @var ProducerInterface
     */
    private $runeProducer;

    /**
     * @var ProducerInterface
     */
    private $itemProducer;

    /**
     * @var StoreServiceInterface DB Service
     */
    private $dbAdapter;

    /**
     * @var LoggerInterface Logger
     */
    private $logger;

    /**
     * @var FetchServiceInterface API Service
     */
    private $apiAdapter;

    /**
     * @var ProducerInterface
     */
    private $championProducer;

    public function __construct(
        LoggerInterface $logger,
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter,
        ProducerInterface $championProducer,
        ProducerInterface $itemProducer,
        ProducerInterface $runeProducer
    ) {
        parent::__construct();
        $this->logger = $logger;
        $this->apiAdapter = $apiAdapter;
        $this->dbAdapter = $dbAdapter;
        $this->championProducer = $championProducer;
        $this->itemProducer = $itemProducer;
        $this->runeProducer = $runeProducer;
    }
    
    /**
     * Configure Command
     */
    protected function configure()
    {
        $this->setName('update:version')
            ->setDescription('API processor command for version data')
            ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Force a refresh of the data.', false);
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input Input data
     * @param OutputInterface $output Output data
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('Checking version data for update');

        if (count($this->dbAdapter->fetch('SELECT * FROM versions', [])) == 0 || $input->getOption('force')) {
            $this->logger->info("Update required");
            $this->updateData($input);
            return;
        }

        $this->logger->info("Skipping update");
    }

    /**
     * Process data and update DB
     *
     * @param InputInterface $input
     */
    private function updateData(InputInterface $input)
    {
        try {
            $this->logger->info("Storing version data");

            $this->dbAdapter->clear();
            $this->dbAdapter->add($this->apiAdapter->fetch([]));
            $this->dbAdapter->store();
            $this->queueUpdates($input->getOption('force'));
            $this->logger->info("Command complete");
        } catch (\Exception $exception) {
            $this->logger->error("Failed to store version data:", ['exception' => $exception]);
        }
    }

    /**
     * Queue new updates
     *
     * @param bool $force Force update of this version
     */
    private function queueUpdates(bool $force)
    {
        foreach ($this->apiAdapter->transfer() as $version) {
            $this->logger->info("Queuing update for version ".$version->getFullVersion());
            $message = serialize([
                'version' => $version->getFullVersion(),
                'force' => $force
            ]);
            $this->championProducer->publish($message);
            $this->itemProducer->publish($message);
            $this->runeProducer->publish($message);
        }
    }
}
