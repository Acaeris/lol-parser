<?php
namespace LeagueOfData\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Psr\Log\LoggerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use LeagueOfData\Service\Json\Version\VersionCollection as ApiCollection;
use LeagueOfData\Service\Sql\Version\VersionCollection as DbCollection;

class VersionUpdateConsumer implements ConsumerInterface
{

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
    private $dbAdapter;

    /**
     * @var FetchServiceInterface
     */
    private $apiAdapter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        LoggerInterface $logger,
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter,
        ProducerInterface $championProducer,
        ProducerInterface $itemProducer,
        ProducerInterface $runeProducer
    ) {
        $this->logger = $logger;
        $this->apiAdapter = $apiAdapter;
        $this->dbAdapter = $dbAdapter;
        $this->championProducer = $championProducer;
        $this->itemProducer = $itemProducer;
        $this->runeProducer = $runeProducer;
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

        if (count($this->dbAdapter->fetch("SELECT * FROM versions", [])) == 0 || $message['force']) {
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
     * @param array $message
     * @return bool Success
     */
    private function updateData(array $message) : bool
    {
        try {
            $this->logger->info("Storing version data");

            $this->dbAdapter->clear();
            $this->dbAdapter->add($this->apiAdapter->fetch([]));
            $this->dbAdapter->store();
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
        foreach ($this->apiAdapter->transfer() as $version) {
            $this->logger->info("Queuing update for version ".$version->getFullVersion());
            $message = serialize([
                'version' => $version->getFullVersion(),
                'force' => $force
            ]);
            $this->championProducer->publish($message);
            $this->itemProducer->publish($message);
            $this->runeProducer->publish($message);
        }
    }
}
