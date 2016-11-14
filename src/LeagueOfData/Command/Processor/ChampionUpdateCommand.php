<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use LeagueOfData\Models\Json\JsonChampions;
use LeagueOfData\Models\Sql\SqlChampions;
use LeagueOfData\Adapters\API\Exception\APIException;

class ChampionUpdateCommand extends ContainerAwareCommand
{
    private $log;
    private $service;
    private $sql;
    private $data = [];

    protected function configure()
    {
        $this->setName('update:champion')
                ->setDescription('API processor command for champion data')
                ->addArgument('release', InputArgument::REQUIRED, 'Version number to process data for')
                ->addArgument('championId', InputArgument::OPTIONAL, 'Champion ID to process data for.'
                    . ' (Will fetch all if not supplied)')
                ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Force a refresh of the data.', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->sql = $this->getContainer()->get('sql-adapter');
        $this->log = $this->getContainer()->get('logger');

        $champDb = new SqlChampions($this->sql, $this->log);

        if (count($champDb->collectAll($input->getArgument('release'))) == 0 || $input->getOption('force')) {
            $this->updateData($input);
        } else {
            $this->log->info('Skipping update for version ' . $input->getArgument('release') . ' as data exists');
        }
    }

    private function fetch($championId, $version)
    {
        $this->log->info("Fetching champions for version: {$version}" . (isset($championId) ? " [{$championId}]" : ""));
        if (!empty($championId)) {
            $this->data = $this->service->collect($championId, $version);
        } else {
            $this->data = $this->service->collectAll($version);
        }
    }

    private function recover(InputInterface $input, $msg, \Exception $e = null)
    {
        $params = '"command" : "update:champion",
                "release" : "' . $input->getArgument('release') . '"';
        if (!empty($input->getArgument('championId'))) {
            $params .= ', "championId" : "' . $input->getArgument('championId') . '"';
        }
        $mq = $this->getContainer()->get('rabbitmq');
        $mq->addProcessToQueue('update:champion', "{ {$params} }");
        $this->log->error($msg . ['exception' => $e]);
    }

    private function updateData(InputInterface $input)
    {
        $this->service = new JsonChampions($this->getContainer()->get('riot-api'));

        try {
            $this->fetch($input->getArgument('championId'), $input->getArgument('release'));
            $this->log->info("Storing champion data for version " . $input->getArgument('release'));

            foreach ($this->data as $champion) {
                $champion->store($this->sql);
            }
        } catch (APIException $e) {
            $this->recover($input, 'Unexpected API response: ', $e);
        } catch (ForeignKeyConstraintViolationException $e) {
            preg_match("/CONSTRAINT `(\w+)`/", $e, $matches);

            if ($matches[1] == 'version') {
                $this->log->info("Requesting refresh of version data");
                $mq = $this->getContainer()->get('rabbitmq');
                $mq->addProcessToQueue('update:version', '{ "command" : "update:version" }');
            }
        }
    }
}
