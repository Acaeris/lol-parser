<?php
namespace LeagueOfData\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use Psr\Log\LoggerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use LeagueOfData\Service\Json\Item\ItemCollection as ApiCollection;
use LeagueOfData\Service\Sql\Item\ItemCollection as DbCollection;

class ItemUpdateConsumer implements ConsumerInterface
{

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

    /**
     * @var array
     */
    private $defaultParams = [
        'region' => 'euw'
    ];

    public function __construct(
        LoggerInterface $logger,
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter
    ) {
        $this->logger = $logger;
        $this->apiAdapter = $apiAdapter;
        $this->dbAdapter = $dbAdapter;
    }

    /**
     * Execute the command
     *
     * @param AMQPMessage $msg Command message from MQ.
     */
    public function execute(AMQPMessage $msg)
    {
        $this->logger->info('Checking Item data for update.');

        $message = array_merge($this->defaultParams, unserialize($msg->body));
        $select = $this->buildRequest($message);

        if (count($this->dbAdapter->fetch($select, $message) == 0 || $message['force'])) {
            $this->logger->info('Update required');
            try {
                $this->dbAdapter->clear();
                $this->dbAdapter->add($this->apiAdapter->fetch($message));
                $this->dbAdapter->store();
                $this->logger->info('Update complete');
                return true;
            } catch (\Exception $exception) {
                $this->logger->error("Failed to store item data:", ['exception' => $exception]);
            }
            return false;
        }

        $this->logger->info("Skipping update");
        return true;
    }

    /**
     * Build a request object
     *
     * @param array $input
     * @return string
     */
    private function buildRequest(array $input) : string
    {
        $select = "SELECT * FROM items WHERE version = :version AND region = :region";

        if (isset($input['itemId'])) {
            $select .= " AND item_id = :item_id";
        }
        
        return $select;
    }
}
