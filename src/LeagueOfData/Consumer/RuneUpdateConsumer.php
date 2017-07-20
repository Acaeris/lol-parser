<?php
namespace LeagueOfData\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use Psr\Log\LoggerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use LeagueOfData\Repository\Rune\JsonRuneRepository;
use LeagueOfData\Repository\Rune\SqlRuneRepository;

class RuneUpdateConsumer implements ConsumerInterface
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
        JsonRuneRepository $apiRepository,
        SqlRuneRepository $dbRepository
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
        $this->logger->info('Checking Rune data for update.');

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
                $this->logger->error("Failed to store rune data:", ['exception' => $exception]);
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
        $select = "SELECT * FROM runes WHERE version = :version AND region = :region";

        if (isset($input['runeId'])) {
            $select .= " AND rune_id = :rune_id";
        }

        return $select;
    }
}
