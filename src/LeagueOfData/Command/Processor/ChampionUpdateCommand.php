<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use LeagueOfData\Models\Json\JsonChampions;
use LeagueOfData\Adapters\API\Exception\APIException;

class ChampionUpdateCommand extends ContainerAwareCommand
{
    private $log;
    private $service;
    private $data = [];

    protected function configure()
    {
        $this->setName('update:champion')
                ->setDescription('API processor command for champion data')
                ->addArgument('release', InputArgument::REQUIRED, 'Version number to process data for')
                ->addArgument('championId', InputArgument::OPTIONAL, 'Champion ID to process data for.'
                    . ' (Will fetch all if not supplied)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log = $this->getContainer()->get('logger');
        $this->service = new JsonChampions($this->getContainer()->get('riot-api'));

        try {
            $this->fetch($input->getArgument('championId'), $input->getArgument('release'));
            $this->log->info("Storing champion data for version " . $input->getArgument('release'));

            foreach ($this->data as $champion) {

                $champion->store($this->getContainer()->get('sql-adapter'));
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
}
