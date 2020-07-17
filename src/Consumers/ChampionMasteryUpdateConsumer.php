<?php

namespace App\Consumers;

use App\Mappers\ChampionMastery\ChampionMasteryMapper;
use App\Repositories\RiotAPI\ChampionMastery\ChampionMasteryRepository as ApiRepository;
use App\Repositories\Database\ChampionMastery\ChampionMasteryRepository as DbRepository;
use DateTime;
use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

/**
 * Champion Mastery Update Consumer
 *
 * @package App\Consumers
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class ChampionMasteryUpdateConsumer
{
    /* @var LoggerInterface Logging service */
    private $log;

    /* @var ApiRepository Champion Mastery API Repository */
    private $api;

    /* @var DbRepository Champion Mastery Database Repository */
    private $dbConn;

    public function __construct(
        LoggerInterface $log,
        ApiRepository $api,
        DbRepository $dbConn
    ) {
        $this->log = $log;
        $this->api = $api;
        $this->dbConn = $dbConn;
    }

    /**
     * Process the entry in the message queue
     * @param AMQPMessage $message Message Queue Entry
     * @return bool Success Status
     * @throws Exception
     */
    public function execute(AMQPMessage $message): bool
    {
        // TODO: Validate message
        $summonerId = $message->body['summonerId'];
        $region = $message->body['region'] ?? 'euw';
        $force = $message->body['force'] ?? false;

        $this->log->info("REQUEST: Update Champion Mastery for Summoner ID {$summonerId} ({$region})");

        $data = $this->dbConn->fetchBySummonerId($summonerId, $region);

        $lastUpdate = new DateTime($data['lastUpdated']);

        if ($lastUpdate->diff(new DateTime())->days > 1 || $force) {
            $this->log->info("          Update required");
        }

        return true;
    }

    public function update(AMQPMessage $message): bool
    {
        $summonerId = $message->body['summonerId'];

        $this->dbConn->store($this->api->fetchBySummonerId($summonerId, $message->body));

        return true;
    }
}
