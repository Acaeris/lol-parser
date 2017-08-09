<?php

namespace LeagueOfData\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use LeagueOfData\Repository\Summoner\JsonSummonerRepository;
use LeagueOfData\Repository\Summoner\SqlSummonerRepository;

class SummonerUpdateCommand extends Command
{

    /**
     * @var JsonSummonerRepository
     */
    private $dbRepository;

    /**
     * @var SqlSummonerRepository
     */
    private $apiRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

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
        JsonSummonerRepository $apiRepository,
        SqlSummonerRepository $dbRepository
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
        $this->setName('update:summoner')
            ->setDescription('API processor command for summoner data')
            ->addOption('summonerId', 'i', InputOption::VALUE_REQUIRED, 'Summoer ID to process data for.')
            ->addOption('summonerName', 's', InputOption::VALUE_REQUIRED, 'Summoner Name to process data for.')
            ->addOption('accountId', 'a', InputOption::VALUE_REQUIRED, 'Account ID to process data for.')
            ->addOption('region', 'r', InputOption::VALUE_REQUIRED, 'Region to fetch data for. (Default "euw")', "euw")
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force a refresh of the data.');
    }

    /**
     * Execute the command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->buildRequest($input);

        $output->writeln('<info>Checking Summoner data for update.</info>');

        if (count($this->dbRepository->fetch($this->select, $this->where)) === 0 || $input->getOption('force')) {
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
        $accountId = $input->getOption('accountId') ?? '';
        $summonerName = $input->getOption('summonerName') ?? '';
        $summonerId = $input->getOption('summonerId') ?? '';
        $output->writeln(
            '<info>Skipping update for summoner:</info> '.$summonerName
            .' [summoner_id: '.$summonerId.', account_id: '.$accountId.']'
        );
    }

    /**
     * Build the request values
     *
     * @param InputInterface $input
     */
    private function buildRequest(InputInterface $input)
    {
        $this->select = "SELECT * FROM summoners WHERE region = :region";
        $this->where['region'] = $input->getOption('region');

        if (null !== $input->getOption('summonerId')) {
            $this->where['summoner_id'] = $input->getOption('summonerId');
            $this->select .= " AND summoner_id = :summoner_id";
        }
        if (null !== $input->getOption('summonerName')) {
            $this->where['summoner_name'] = $input->getOption('summonerName');
            $this->select .= " AND summoner_name = :summoner_name";
        }
        if (null !== $input->getOption('accountId')) {
            $this->where['account_id'] = $input->getOption('accountId');
            $this->select .= " AND account_id = :account_id";
        }
    }
}
