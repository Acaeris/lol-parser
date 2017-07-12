<?php

namespace LeagueOfData\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Service\Sql\Match\MatchCollection as DbCollection;
use LeagueOfData\Service\Json\MatchList\MatchListCollection as ApiCollection;

class MatchListUpdateCommand extends Command
{

    /**
     * @var DbCollection
     */
    private $dbAdapter;

    /**
     * @var ApiCollection
     */
    private $apiAdapter;

    /**
     * @var LoggerInterface
     */
    private $logger;

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
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('update:matchlist')
            ->setDescription('API processor command for match list data')
            ->addArgument('accountId', InputArgument::REQUIRED, 'Account ID to process match list for')
            ->addOption('region', 'r', InputOption::VALUE_REQUIRED, 'Region to fetch data for. (Default "euw")', "euw")
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force a refresh of the data.')
            ->addOption('all', 'a', InputOption::VALUE_NONE, 'Fetch entire history. (Defaults to last 20 matches)');
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('Checking Matchlist data for update.');

        $where = [
            'all' => $input->getOption('all')
        ];

        // TODO: Add check for last time this account was updated.
        try {
            $this->dbAdapter->clear();
            $this->dbAdapter->add($this->apiAdapter->fetch($where));
            $this->dbAdapter->store();
            $this->logger->info("Command complete");
        } catch (Exception $exception) {
            $this->logger->error("Failed to store matchlist data:", ['exception' => $exception]);
        }
        return;
    }
}
