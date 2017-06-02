<?php

namespace LeagueOfData\Service\Json;

use Psr\Log\LoggerInterface;
use LeagueOfData\Library\Mapper\KeyMapper;
use LeagueOfData\Service\Interfaces\ChampionSpellsServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Models\Interfaces\ChampionSpellInterface;
use LeagueOfData\Models\Champion\ChampionSpell;
use LeagueOfData\Models\Champion\ChampionSpellResource;

/**
 * Champion Spells object JSON factory
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonChampionSpells implements ChampionSpellsServiceInterface
{
    /** @var AdapterInterface **/
    private $adapter;
    /** @var LoggerInterface **/
    private $log;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->log = $log;
        $this->adapter = $adapter;
    }

    /**
     * Factory to create Champion Spells objects from JSON
     *
     * @param array $spell
     * @return ChampionSpellInterface
     */
    public function create(array $spell) : ChampionSpellInterface
    {
        return new ChampionSpell(
            $spell['id'],
            $spell['name'],
            KeyMapper::getKeyForSpell($spell['key']),
            $spell['image']['full'],
            $spell['maxrank'],
            $spell['description'],
            $spell['tooltip'],
            $spell['cooldown'],
            $spell['range'],
            $spell['effect'],
            $spell['vars'],
            new ChampionSpellResource($spell['costType'], $spell['cost']),
            $spell['version'],
            $spell['region']
        );
    }

    /**
     * Add all champion spells objects to internal array
     *
     * @param array $spells ChampionSpell objects
     */
    public function add(array $spells)
    {
        foreach ($spells as $spell) {
            $this->spells[$spell->getID()] = $spell;
        }
    }

    /**
     * Fetch Champion spells
     *
     * @param RequestInterface $request
     * @throws Exception
     */
    public function fetch(RequestInterface $request) : array
    {
        throw new \Exception("Fetch method for Champion Spells from API is not currently implemented");
    }

    /**
     * Store the champion spells in the database
     */
    public function store()
    {
        throw new \Exception("Store method not available for API");
    }

    /**
     * Get collection of champions' spell for transfer to a different process.
     *
     * @return array ChampionStats objects
     */
    public function transfer() : array
    {
        return $this->champions;
    }
}
