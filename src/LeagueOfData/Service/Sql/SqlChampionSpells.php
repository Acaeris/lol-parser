<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Service\Interfaces\ChampionSpellsServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Adapters\Request\ChampionSpellRequest;
use LeagueOfData\Models\Interfaces\ChampionSpellInterface;
use LeagueOfData\Models\Interfaces\ChampionSpellResourceInterface;
use LeagueOfData\Models\Champion\ChampionSpell;
use LeagueOfData\Models\Champion\ChampionSpellVars;
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
     * @var AdapterInterface
     */
    private $dbAdapter;
    /**
     * @var array
     */
    private $spells;

    public function __construct(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $this->dbAdapter = $adapter;
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
                $this->spells[$spell->getSpellID()] = $spell;
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
            $champion['spell_id'],
            $champion['spell_name'],
            $champion['spell_key'],
            $champion['image_name'],
            $champion['max_rank'],
            $champion['description'],
            $champion['tooltip'],
            json_decode($champion['cooldowns']),
            json_decode($champion['ranges']),
            json_decode($champion['effects']),
            $this->createVariables(json_decode($champion['variables'], true)),
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
        $results = $this->dbAdapter->fetch($request);
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
            $request = new ChampionSpellRequest(
                [
                    'champion_id' => $spell->getChampionID(),
                    'version' => $spell->getVersion(),
                    'spell_id' => $spell->getSpellID()
                ],
                'spell_id',
                [
                    'champion_id' => $spell->getChampionID(),
                    'spell_id' => $spell->getSpellID(),
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
                    'resource' => json_encode($spell->getResource()),
                    'version' => $spell->getVersion(),
                    'region' => $spell->getRegion()
                ]
            );

            if ($this->dbAdapter->fetch($request)) {
                $this->dbAdapter->update($request);

                continue;
            }
            $this->dbAdapter->insert($request);
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
                $this->spell[$spell['spell_id']] = $this->create($spell);
            }
        }
    }

    /**
     * Create Champion Spell Variables objects
     *
     * @param array $variables
     * @return array ChampionSpellVars
     */
    private function createVariables(array $variables) : array
    {
        $output = [];

        foreach ($variables as $var) {
            $output[] = new ChampionSpellVars($var['link'], $var['coeff'], $var['key']);
        }

        return $output;
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
}
