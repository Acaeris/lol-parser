<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Service\Interfaces\ChampionSpellsServiceInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Adapters\Request;
use LeagueOfData\Models\Interfaces\ChampionSpellInterface;
use LeagueOfData\Models\Interfaces\ChampionSpellResourceInterface;
use LeagueOfData\Models\Champion\ChampionSpell;
use LeagueOfData\Models\Champion\ChampionSpellResource;

/**
 * Champion Spells object SQL factory
 *
 * @package LeagueOfData\Service\Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class SqlChampionSpells implements ChampionSpellsServiceInterface
{
    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * @var Connection
     */
    private $dbConn;

    /**
     * @var array
     */
    private $spells;

    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->dbConn = $connection;
        $this->log = $logger;
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
            $this->log->error('Incorrect object supplied to Champion Spells service', [$spell]);
        }
    }

    /**
     * Factory to create Champion Spell objects from SQL
     *
     * @param array $champion
     * @return ChampionSpellInterface
     */
    public function create(array $champion) : ChampionSpellInterface
    {
        return new ChampionSpell(
            $champion['champion_id'],
            $champion['spell_name'],
            $champion['spell_key'],
            $champion['image_name'],
            $champion['max_rank'],
            $champion['description'],
            $champion['tooltip'],
            json_decode($champion['cooldowns']),
            json_decode($champion['ranges']),
            json_decode($champion['effects']),
            json_decode($champion['variables']),
            $this->createResource(json_decode($champion['resource'], true)),
            $champion['version'],
            $champion['region']
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
        $this->log->debug("Fetch champion spells from DB");
        $request->requestFormat(Request::TYPE_SQL);
        $results = $this->dbConn->fetchAll($request->query(), $request->where());
        $this->spells = [];
        $this->processResults($results);
        $this->log->debug(count($this->spells)." spells fetched from DB");

        return $this->spells;
    }

    /**
     * Store the champion spells in the database
     */
    public function store()
    {
        $this->log->debug("Storing ".count($this->spells)." new/updated spells");

        foreach ($this->spells as $spell) {
            $select = 'SELECT spell_name FROM champion_spells WHERE champion_id = :champion_id AND version = :version'
                . ' AND spell_name = :spell_name AND region = :region';
            $where = [
                'champion_id' => $spell->getChampionID(),
                'version' => $spell->getVersion(),
                'spell_name' => $spell->getSpellName(),
                'region' => $spell->getRegion()
            ];

            if ($this->dbConn->fetchAll($select, $where)) {
                $this->dbConn->update('champion_spells', $this->convertSpellToArray($spell), $where);

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
     * Convert result data into ChampionStats objects
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
