<?php

namespace LeagueOfData\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use LeagueOfData\Repository\Rune\JsonRuneRepository;
use LeagueOfData\Repository\Rune\SqlRuneRepository;

class RuneUpdateCommand extends Command
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
        JsonRuneRepository $apiRepository,
        SqlRuneRepository $dbRepository
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
        $this->setName('update:rune')
            ->setDescription('API processor command for rune data')
            ->addArgument('release', InputArgument::REQUIRED, 'Version number to process data for.')
            ->addOption(
                'runeId', 'i', InputOption::VALUE_REQUIRED, 'Rune ID to process data for.'
                .' (Will fetch all if not supplied)'
            )
            ->addOption('region', 'r', InputOption::VALUE_REQUIRED, 'Region to fetch data for. (Default "euw")', 'euw')
            ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Force a refresh of the data', false);
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

        $output->writeln('Checking Rune data for update');

        if (count($this->dbRepository->fetch($this->select, $this->where)) == 0 || $input->getOption('force')) {
            $output->writeln("Update required");
            try {
                $this->dbRepository->clear();
                $this->dbRepository->add($this->apiRepository->fetch($this->where));
                $this->dbRepository->store();
                $output->writeln("Command complete");
            } catch (\Exception $exception) {
                $this->logger->error("Failed to store rune data:", ['exception' => $exception]);
            }
            return;
        }

        $output->writeln('Skipping update for version '.$input->getArgument('release').' as data exists');
    }

    /**
     * Build a request object
     *
     * @param InputInterface $input
     */
    private function buildRequest(InputInterface $input)
    {
        $this->select = "SELECT * FROM runes WHERE version = :version AND region = :region";
        $this->where = [
            'version' => $input->getArgument('release'),
            'region' => $input->getOption('region')
        ];

        if (null !== $input->getOption('runeId')) {
            $this->where['rune_id'] = $this->getOption('runeId');
            $this->select .= " AND rune_id = :rune_id";
        }
    }
}
