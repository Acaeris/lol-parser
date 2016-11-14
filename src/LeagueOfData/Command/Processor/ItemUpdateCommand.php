<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use LeagueOfData\Adapters\API\Exception\APIException;
use LeagueOfData\Models\Json\JsonItems;
use LeagueOfData\Models\Sql\SqlItems;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class ItemUpdateCommand extends ContainerAwareCommand
{
    private $log;
    private $service;
    private $sql;
    private $data = [];

    protected function configure()
    {
        $this->setName('update:item')
            ->setDescription('API processor command for item data')
            ->addArgument('release', InputArgument::REQUIRED, 'Version number to process data for')
            ->addArgument('itemId', InputArgument::OPTIONAL, 'Item ID to process data for.'
                . ' (Will fetch all if not supplied)')
            ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Force a refresh of the data.', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->sql = $this->getContainer()->get('sql-adapter');
        $this->log = $this->getContainer()->get('logger');

        $itemDb = new SqlItems($this->sql, $this->log);

        if (count($itemDb->collectAll($input->getArgument('release'))) == 0 || $this->getOption('force')) {
            $this->updateData($input);
        } else {
            $this->log->info('Skipping update for version ' . $input->getArgument('release') . ' as data exists');
        }
    }

    private function fetch($itemId, $version)
    {
        $this->log->info("Fetching items for version {$version}" . (isset($itemId) ? " [{$itemId}]" : ""));
        if (!empty($itemId)) {
            $this->data = $this->service->collect($itemId, $version);
        } else {
            $this->data = $this->service->collectAll($version);
        }
    }

    private function recover(InputInterface $input, $msg, \Exception $e = null)
    {
        $params = '"command" : "update:item",
            "release" : "' . $input->getArgument('release') . '"';
        if (!empty($input->getArgument('itemId'))) {
            $params .= ', "itemId" : "' . $input->getArgument('itemId') . '"';
        }
        $mq = $this->getContainer()->get('rabbitmq');
        $mq->addProcessToQueue('update:item', "{ {$params} }");
        $this->log->error($msg . ['exception' => $e]);
    }

    private function updateData(InputInterface $input)
    {
        $this->service = new JsonItems($this->getContainer()->get('riot-api'));

        try {
            $this->fetch($input->getArgument('itemId'), $input->getArgument('release'));
            $this->log->info("Storing item data for version " . $input->getArgument('release'));

            foreach ($this->data as $item) {
                $item->store($this->getContainer()->get('sql-adapter'));
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
