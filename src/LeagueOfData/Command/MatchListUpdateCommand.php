<?php

namespace LeagueOfData\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Repository\Match\SqlMatchRepository;
use LeagueOfData\Repository\MatchList\JsonMatchListRepository;

class MatchListUpdateCommand extends Command
{

    /**
     * @var SqlMatchRepository
     */
    private $dbRepository;

    /**
     * @var ApiCollection
     */
    private $apiRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        LoggerInterface $logger,
        JsonMatchListRepository $apiRepository,
        SqlMatchRepository $dbRepository
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
        $output->writeln('<info>Checking Matchlist data for update.</info>');

        $where = [
            'all' => $input->getOption('all'),
            'account_id' => $input->getArgument('accountId')
        ];

        // TODO: Add check for last time this account was updated.
        try {
            $this->dbRepository->clear();
            $this->dbRepository->add($this->apiRepository->fetch($where));
            $this->dbRepository->store();
            $output->writeln("<info>Command complete</info>");
        } catch (Exception $exception) {
            $this->logger->error("Failed to store matchlist data:", ['exception' => $exception]);
        }
        return;
    }
}
