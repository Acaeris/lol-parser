<?php
namespace LeagueOfData\Repository\Champion;

use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Repository\StoreRepositoryInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Champion\Champion;
use LeagueOfData\Entity\Champion\ChampionInterface;

/**
 * Champion object DB repository.
 *
 * @package LeagueOfData\Repository
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class SqlChampionRepository implements StoreRepositoryInterface
{

    /**
     * @var Connection DB connection
     */
    private $dbConn;

    /**
     * @var LoggerInterface Logger
     */
    private $log;

    /**
     * @var SqlChampionStatsRepository Champion Stat repository
     */
    private $statRepository;

    /**
     * @var SqlChampionSpellRepository Champion Spell repository
     */
    private $spellRepository;

    /**
     * @var SqlChampionPassiveRepository Champion Passive repository
     */
    private $passiveRepository;

    /**
     * @var array Champion objects
     */
    private $champions = [];

    /**
     * Setup Champion repository
     *
     * @param Connection                   $connection
     * @param LoggerInterface              $log
     * @param SqlChampionStatsRepository   $statRepository
     * @param SqlChampionSpellRepository   $spellRepository
     * @param SqlChampionPassiveRepository $passiveRepository
     */
    public function __construct(Connection $connection,
        LoggerInterface $log,
        SqlChampionStatsRepository $statRepository,
        SqlChampionSpellRepository $spellRepository,
        SqlChampionPassiveRepository $passiveRepository)
    {
        $this->dbConn = $connection;
        $this->log = $log;
        $this->statRepository = $statRepository;
        $this->spellRepository = $spellRepository;
        $this->passiveRepository = $passiveRepository;
    }

    /**
     * Add champion objects to internal array
     *
     * @param array $champions Champion objects
     */
    public function add(array $champions)
    {
        foreach ($champions as $champion) {
            if ($champion instanceof ChampionInterface) {
                $this->champions[$champion->getChampionID()] = $champion;
                continue;
            }
            $this->log->error('Incorrect object supplied to Champion repository', [$champion]);
        }
    }

    /**
     * Clear the internal collection
     */
    public function clear()
    {
        $this->champions = [];
    }

    /**
     * Fetch Champions
     *
     * @param string $query SQL query
     * @param array  $where SQL where parameters
     * @return array Champion Objects
     */
    public function fetch(string $query, array $where = []): array
    {
        $this->log->debug("Fetching champions from DB");
        $results = $this->dbConn->fetchAll($query, $where);
        $this->champions = [];
        $this->processResults($results);
        $this->log->debug(count($this->champions)." champions fetched from DB");

        return $this->champions;
    }

    /**
     * Store the champion objects in the database
     */
    public function store()
    {
        $this->log->debug("Storing ".count($this->champions)." new/updated champions");

        $select = "SELECT champion_name FROM champions WHERE champion_id = :champion_id AND version = :version"
                . " AND region = :region";

        foreach ($this->champions as $champion) {
            $this->statRepository->add([$champion->getStats()]);
            $this->spellRepository->add($champion->getSpells());
            $this->passiveRepository->add([$champion->getPassive()]);

            if ($this->dbConn->fetchAll($select, $champion->getKeyData())) {
                $this->dbConn->update('champions', $this->convertChampionToArray($champion), $champion->getKeyData());

                continue;
            }
            $this->dbConn->insert('champions', $this->convertChampionToArray($champion));
        }

        $this->statRepository->store();
        $this->spellRepository->store();
        $this->passiveRepository->store();
    }

    /**
     * Get collection of champions for transfer to a different process.
     *
     * @return array Champion objects
     */
    public function transfer(): array
    {
        return $this->champions;
    }

    /**
     * Create the champion object from array data
     *
     * @param array $champion
     * @return EntityInterface
     */
    public function create(array $champion): EntityInterface
    {
        $where = [
            'version' => $champion['version'],
            'region' => $champion['region'],
            'champion_id' => $champion['champion_id'],
        ];
        $stats = $this->statRepository->fetch("SELECT * FROM champion_stats WHERE version = :version AND region = :region"
            . " AND champion_id = :champion_id", $where);
        $spells = $this->spellRepository->fetch("SELECT * FROM champion_spells WHERE version = :version"
            . " AND region = :region AND champion_id = :champion_id", $where);
        $passive = $this->passiveRepository->fetch("SELECT * FROM champion_passives WHERE version = :version"
            . " AND region = :region AND champion_id = :champion_id", $where);
        return new Champion(
            $champion['champion_id'],
            $champion['champion_name'],
            $champion['title'],
            $champion['resource_type'],
            explode('|', $champion['tags']),
            $stats[$champion['champion_id']],
            $passive[$champion['champion_id']],
            $spells,
            $champion['image_name'],
            $champion['version'],
            $champion['region']
        );
    }

    /**
     * Converts Champion object into SQL data array
     *
     * @param ChampionInterface $champion
     * @return array
     */
    private function convertChampionToArray(ChampionInterface $champion): array
    {
        return [
            'champion_id' => $champion->getChampionID(),
            'champion_name' => $champion->getName(),
            'title' => $champion->getTitle(),
            'resource_type' => $champion->getResourceType(),
            'tags' => $champion->getTagsAsString(),
            'image_name' => $champion->getImageName(),
            'version' => $champion->getVersion(),
            'region' => $champion->getRegion()
        ];
    }

    /**
     * Convert result data into Champion objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            if (!is_array($results)) {
                $results = [$results];
            }

            foreach ($results as $champion) {
                $this->champions[$champion['champion_id']] = $this->create($champion);
            }
        }
    }
}
