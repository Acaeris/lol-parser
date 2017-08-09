<?php
namespace LeagueOfData\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use Psr\Log\LoggerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use LeagueOfData\Repository\Champion\JsonChampionRepository;
use LeagueOfData\Repository\Champion\SqlChampionRepository;

class ChampionUpdateConsumer implements ConsumerInterface
{

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

    /**
     * @var array
     */
    private $defaultParams = [
        'region' => 'euw'
    ];

    public function __construct(
        LoggerInterface $logger,
        JsonChampionRepository $apiRepository,
        SqlChampionRepository $dbRepository
    ) {
        $this->logger = $logger;
        $this->apiRepository = $apiRepository;
        $this->dbRepository = $dbRepository;
    }

    /**
     * Execute the command
     *
     * @param AMQPMessage $msg Command message from MQ.
     */
    public function execute(AMQPMessage $msg)
    {
        $this->logger->info('Checking Champion data for update.');

        $message = array_merge($this->defaultParams, unserialize($msg->body));
        $select = $this->buildRequest($message);

        if (count($this->dbRepository->fetch($select, $message) == 0 || $message['force'])) {
            $this->logger->info('Update required');
            try {
                $this->dbRepository->clear();
                $this->dbRepository->add($this->apiRepository->fetch($message));
                $this->dbRepository->store();
                $this->logger->info('Update complete');
                return true;
            } catch (\Exception $exception) {
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
     * @param  array $input
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
