<?php
namespace LeagueOfData\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Psr\Log\LoggerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use LeagueOfData\Repository\Version\JsonVersionRepository;
use LeagueOfData\Repository\Version\SqlVersionRepository;

class VersionUpdateConsumer implements ConsumerInterface
{

    /**
     * @var ProducerInterface
     */
    private $masteryProducer;

    /**
     * @var ProducerInterface
     */
    private $runeProducer;

    /**
     * @var ProducerInterface
     */
    private $itemProducer;

    /**
     * @var ProducerInterface
     */
    private $championProducer;

    /**
     * @var StoreServiceInterface
     */
    private $dbRepository;

    /**
     * @var FetchServiceInterface
     */
    private $apiRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        LoggerInterface $logger,
        JsonVersionRepository $apiRepository,
        SqlVersionRepository $dbRepository,
        ProducerInterface $championProducer,
        ProducerInterface $itemProducer,
        ProducerInterface $runeProducer,
        ProducerInterface $masteryProducer
    ) {
        $this->logger = $logger;
        $this->apiRepository = $apiRepository;
        $this->dbRepository = $dbRepository;
        $this->championProducer = $championProducer;
        $this->itemProducer = $itemProducer;
        $this->runeProducer = $runeProducer;
        $this->masteryProducer = $masteryProducer;
    }

    /**
     * Execute the command
     *
     * @param AMQPMessage $msg Command message from MQ.
     */
    public function execute(AMQPMessage $msg)
    {
        $this->logger->info('Checking version data for update');

        $message = unserialize($msg->body);

        if (count($this->dbRepository->fetch("SELECT * FROM versions", [])) == 0 || $message['force']) {
            $this->logger->info("Update required");
            if ($this->updateData($message)) {
                return true;
            }
            return false;
        }

        $this->logger->info("Skipping update");
        return true;
    }

    /**
     * Process data and update DB
     *
     * @param  array $message
     * @return bool Success
     */
    private function updateData(array $message) : bool
    {
        try {
            $this->logger->info("Storing version data");

            $this->dbRepository->clear();
            $this->dbRepository->add($this->apiRepository->fetch([]));
            $this->dbRepository->store();
            $this->queueUpdates($message['force']);
            $this->logger->info('Update complete');
            return true;
        } catch (\Exception $exception) {
            $this->logger->error("Failed to store version data:", ['exception' => $exception]);
        }
        return false;
    }

    /**
     * Queue new updates
     *
     * @param bool $force Force update of this version
     */
    private function queueUpdates(bool $force)
    {
        foreach ($this->apiRepository->transfer() as $version) {
            $this->logger->info("Queuing update for version ".$version->getFullVersion());
            $message = serialize(
                [
                    'version' => $version->getFullVersion(),
                    'force' => $force
                ]
            );
            $this->championProducer->publish($message);
            $this->itemProducer->publish($message);
            $this->runeProducer->publish($message);
            $this->masteryProducer->publish($message);
        }
    }
}
