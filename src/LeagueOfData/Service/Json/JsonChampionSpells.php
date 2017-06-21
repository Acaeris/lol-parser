<?php
namespace LeagueOfData\Service\Json;

use Psr\Log\LoggerInterface;
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

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * @var array
     */
    private $spells;

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
    public function create(array $spell): ChampionSpellInterface
    {
        $keys = ["Q", "W", "E", "R"];

        return new ChampionSpell(
            $spell['id'],
            $spell['name'],
            $keys[$spell['number']],
            $spell['image']['full'],
            $spell['maxrank'],
            $spell['description'],
            str_replace('"', "'", isset($spell['tooltip']) ? $spell['tooltip'] : ''),
            $spell['cooldown'],
            $spell['range'],
            $spell['effect'],
            isset($spell['vars']) ? $spell['vars'] : [],
            new ChampionSpellResource(
                isset($spell['costType']) ? $spell['costType'] : '',
                isset($spell['cost']) ? $spell['cost'] : []
            ),
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
            $this->spells[$spell->getSpellName()] = $spell;
        }
    }

    /**
     * Fetch Champion spells
     *
     * @param RequestInterface $request
     * @throws Exception
     */
    public function fetch(RequestInterface $request): array
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
    public function transfer(): array
    {
        return $this->spells;
    }
}
