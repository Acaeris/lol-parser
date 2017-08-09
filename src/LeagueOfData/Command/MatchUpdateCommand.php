<?php

namespace LeagueOfData\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use LeagueOfData\Repository\Match\SqlMatchRepository;
use LeagueOfData\Repository\Match\JsonMatchRepository;

class MatchUpdateCommand extends Command
{

    /**
     * @var SqlMatchRepository
     */
    private $dbRepository;

    /**
     * @var JsonMatchRepository
     */
    private $apiRespository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        LoggerInterface $logger,
        JsonMatchRepository $apiRepository,
        SqlMatchRepository $dbRepository
    ) {
        parent::__construct();
        $this->logger = $logger;
        $this->apiRespository = $apiRepository;
        $this->dbRepository = $dbRepository;
    }

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('update:match')
            ->setDescription('API processor command for match data')
            ->addArgument('matchId', InputArgument::REQUIRED, 'Match ID to process data for')
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
        $output->writeln('<info>Checking Match data for update.</info>');

        $select = "SELECT * FROM matches WHERE match_id = :match_id AND region = :region";
        $where = [
            'match_id' => $input->getArgument('matchId'),
            'region' => $input->getOption('region')
        ];

        if (count($this->dbRepository->fetch($select, $where)) === 0 || $input->getOption('force')) {
            $output->writeln("<info>Update required</info>");
            try {
                $this->dbRepository->clear();
                $this->dbRepository->add($this->apiRespository->fetch($where));
                $this->dbRepository->store();
            } catch (\Exception $exception) {
                $this->logger->error("Failed to store match data:", ['exception' => $exception]);
            }
            return;
        }

        $output->writeln('<info>Skipping update for match:</info> '.$input->getArgument('matchId'));
    }
}
