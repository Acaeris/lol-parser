<?php
namespace App\Consumers;

use App\Services\FetchServiceInterface;
use App\Services\StoreServiceInterface;
use Exception;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use Psr\Log\LoggerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use App\Services\Json\Champion\ChampionCollection as ApiCollection;
use App\Services\Sql\Champion\ChampionCollection as DbCollection;

class ChampionUpdateConsumer implements ConsumerInterface
{
    /* @var StoreServiceInterface Database Adapter */
    private $dbAdapter;

    /* @var FetchServiceInterface API Adapter */
    private $apiAdapter;

    /* @var LoggerInterface Logging Service */
    private $logger;

    /* @var array Default Routing Parameters */
    private $defaultParams = ['region' => 'euw'];

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
     * @return bool
     */
    public function execute(AMQPMessage $msg): bool
    {
        $this->logger->info('Checking Champion data for update.');

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
            } catch (Exception $exception) {
                $this->logger->error("Failed to store champion data:", ['exception' => $exception]);
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
        $select = "SELECT * FROM champions WHERE version = :version AND region = :region";

        if (isset($input['championId'])) {
            $select .= " AND champion_id = :champion_id";
        }

        return $select;
    }
}
