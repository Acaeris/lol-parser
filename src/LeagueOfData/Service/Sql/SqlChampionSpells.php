<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Service\StoreServiceInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Adapters\Request;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Champion\ChampionSpellInterface;
use LeagueOfData\Entity\Champion\ChampionSpellResourceInterface;
use LeagueOfData\Entity\Champion\ChampionSpell;
use LeagueOfData\Entity\Champion\ChampionSpellResource;

/**
 * Champion Spells object SQL factory
 *
 * @package LeagueOfData\Service\Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class SqlChampionSpells implements StoreServiceInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Connection
     */
    private $dbConn;

    /**
     * @var array
     */
    private $spells;

    public function __construct(Connection $dbConn, LoggerInterface $logger)
    {
        $this->dbConn = $dbConn;
        $this->logger = $logger;
    }

    /**
     * Add all champion spells objects to internal array
     *
     * @param array $spells ChampionSpell objects
     */
    public function add(array $spells)
    {
        foreach ($spells as $spell) {
            if ($spell instanceof ChampionSpellInterface) {
                $this->spells[$spell->getSpellName()] = $spell;
                continue;
            }
            $this->logger->error('Incorrect object supplied to Champion Spells service', [$spell]);
        }
    }

    /**
     * Factory to create Champion Spell objects from SQL
     *
     * @param array $spell
     * @return EntityInterface
     */
    public function create(array $spell) : EntityInterface
    {
        return new ChampionSpell(
            $spell['champion_id'],
            $spell['spell_name'],
            $spell['spell_key'],
            $spell['image_name'],
            $spell['max_rank'],
            $spell['description'],
            $spell['tooltip'],
            json_decode($spell['cooldowns']),
            json_decode($spell['ranges']),
            json_decode($spell['effects']),
            json_decode($spell['variables']),
            $this->createResource(json_decode($spell['resource'], true)),
            $spell['version'],
            $spell['region']
        );
    }

    /**
     * Fetch Champions Spells
     *
     * @param RequestInterface $request
     * @return array ChampionSpell Objects
     */
    public function fetch(RequestInterface $request) : array
    {
        $this->logger->debug("Fetch champion spells from DB");
        $request->requestFormat(Request::TYPE_SQL);
        $results = $this->dbConn->fetchAll($request->query(), $request->where());
        $this->spells = [];
        $this->processResults($results);
        $this->logger->debug(count($this->spells)." spells fetched from DB");

        return $this->spells;
    }

    /**
     * Store the champion spells in the database
     */
    public function store()
    {
        $this->logger->debug("Storing ".count($this->spells)." new/updated spells");

        foreach ($this->spells as $spell) {
            $select = 'SELECT spell_name FROM champion_spells WHERE champion_id = :champion_id AND version = :version'
                . ' AND spell_name = :spell_name AND region = :region';

            if ($this->dbConn->fetchAll($select, $spell->getKeyData())) {
                $this->dbConn->update('champion_spells', $this->convertSpellToArray($spell), $spell->getKeyData());

                continue;
            }

            $this->dbConn->insert('champion_spells', $this->convertSpellToArray($spell));
        }
    }

    /**
     * Get collection of champions' spells for transfer to a different process.
     *
     * @return array ChampionSpell objects
     */
    public function transfer() : array
    {
        return $this->spells;
    }

    /**
     * Convert result data into ChampionSpells objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            foreach ($results as $spell) {
                $this->spells[$spell['spell_name']] = $this->create($spell);
            }
        }
    }

    /**
     * Create Champion Spell Resource object
     *
     * @param array $resource
     * @return ChampionSpellResourceInterface
     */
    private function createResource(array $resource) : ChampionSpellResourceInterface
    {
        return new ChampionSpellResource($resource['name'], $resource['values']);
    }

    /**
     * Converts Spell object into SQL data array
     *
     * @param ChampionSpell $spell
     * @return array
     */
    private function convertSpellToArray(ChampionSpell $spell) : array
    {
        return [
            'champion_id' => $spell->getChampionID(),
            'spell_name' => $spell->getSpellName(),
            'spell_key' => $spell->getKey(),
            'image_name' => $spell->getImageName(),
            'max_rank' => $spell->getMaxRank(),
            'description' => $spell->getDescription(),
            'tooltip' => $spell->getTooltip(),
            'cooldowns' => json_encode($spell->getCooldowns()),
            'ranges' => json_encode($spell->getRanges()),
            'effects' => json_encode($spell->getEffects()),
            'variables' => json_encode($spell->getVars()),
            'resource' => json_encode([
                'name' => $spell->getResource()->getName(),
                'values' => $spell->getResource()->getValues()
            ]),
            'version' => $spell->getVersion(),
            'region' => $spell->getRegion()
        ];
    }
}
