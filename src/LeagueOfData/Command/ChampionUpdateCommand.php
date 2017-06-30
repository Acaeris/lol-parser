<?php

namespace LeagueOfData\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Psr\Log\LoggerInterface;
use LeagueOfData\Service\Sql\Champion\ChampionCollection as DbCollection;
use LeagueOfData\Service\Json\Champion\ChampionCollection as ApiCollection;

class ChampionUpdateCommand extends Command
{
    /**
     * @var LoggerInterface Logger
     */
    private $logger;

    /**
     * @var ChampionService API Service
     */
    private $apiAdapter;

    /**
     * @var Connection DB Service
     */
    private $dbAdapter;

    /**
     * @var string Select Query
     */
    private $select;

    /**
     * @var array Where parameters
     */
    private $where;

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
        $this->setName('update:champion')
            ->setDescription('API processor command for champion data')
            ->addArgument('release', InputArgument::REQUIRED, 'Version number to process data for')
            ->addOption('championId', 'i', InputOption::VALUE_REQUIRED, 'Champion ID to process data for.'
                . ' (Will fetch all if not supplied)')
            ->addOption('region', 'r', InputOption::VALUE_REQUIRED, 'Region to fetch data for. (Default "euw")', "euw")
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force a refresh of the data.');
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->buildRequest($input);

        $this->logger->info('Checking Champion data for update. [v: '.$input->getArgument('release').']');

        if ($this->dbAdapter->fetch($this->select, $this->where) == 0 || $input->getOption('force')) {
            $this->logger->info("Update required");
            try {
                $this->dbAdapter->clear();
                $this->dbAdapter->add($this->apiAdapter->fetch($this->where));
                $this->dbAdapter->store();
                $this->logger->info("Command complete");
            } catch (\Exception $exception) {
                $this->logger->error("Failed to store item data:", ['exception' => $exception]);
            }
            return;
        }

        $this->logger->info('Skipping update for version ' . $input->getArgument('release') . ' as data exists');
    }

    /**
     * Build a request object
     *
     * @param InputInterface $input
     */
    private function buildRequest(InputInterface $input)
    {
        $this->select = "SELECT * FROM champions WHERE version = :version AND region = :region";
        $this->where = [
            'version' => $input->getArgument('release'),
            'region' => $input->getOption('region')
        ];

        if (null !== $input->getOption('championId')) {
            $this->where['champion_id'] = $input->getOption('championId');
            $this->select .= " AND champion_id = :champion_id";
        }
    }
}
