<?php

namespace LeagueOfData\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Psr\Log\LoggerInterface;
use LeagueOfData\Repository\Champion\SqlChampionRepository;
use LeagueOfData\Repository\Champion\JsonChampionRepository;

class ChampionUpdateCommand extends Command
{
    /**
     * @var LoggerInterface Logger
     */
    private $logger;

    /**
     * @var JsonChampionRepository API Repository
     */
    private $apiRepository;

    /**
     * @var SqlChampionRepository DB Repository
     */
    private $dbRepository;

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
        JsonChampionRepository $apiRepository,
        SqlChampionRepository $dbRepository
    ) {
        parent::__construct();
        $this->logger = $logger;
        $this->apiRepository = $apiRepository;
        $this->dbRepository = $dbRepository;
    }

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('update:champion')
            ->setDescription('API processor command for champion data')
            ->addArgument('release', InputArgument::REQUIRED, 'Version number to process data for')
            ->addOption(
                'championId', 'i', InputOption::VALUE_REQUIRED, 'Champion ID to process data for.'
                .' (Will fetch all if not supplied)'
            )
            ->addOption('region', 'r', InputOption::VALUE_REQUIRED, 'Region to fetch data for. (Default "euw")', "euw")
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force a refresh of the data.');
    }

    /**
     * Execute the command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->buildRequest($input);

        $output->writeln('<info>Checking Champion data for update.</info> [v: '.$input->getArgument('release').']');

        if (count($this->dbRepository->fetch($this->select, $this->where)) == 0 || $input->getOption('force')) {
            $output->writeln("<info>Update required</info>");
            try {
                $this->dbRepository->clear();
                $this->dbRepository->add($this->apiRepository->fetch($this->where));
                $this->dbRepository->store();
                $output->writeln("<info>Command complete</info>");
            } catch (\Exception $exception) {
                $this->logger->error("Failed to store champion data:", ['exception' => $exception]);
            }
            return;
        }

        $output->writeln('<info>Skipping update as data exists</info>');
    }

    /**
     * Build the request values
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
