<?php
namespace LeagueOfData\Repository\Champion;

use Psr\Log\LoggerInterface;
use LeagueOfData\Repository\FetchRepositoryInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Champion\ChampionSpell;
use LeagueOfData\Entity\Champion\ChampionSpellResource;

/**
 * Champion Spells object API repository
 *
 * @package LeagueOfData\Repository
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class JsonChampionSpellRepository implements FetchRepositoryInterface
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
     * @param  array $spell
     * @return EntityInterface
     */
    public function create(array $spell): EntityInterface
    {
        $keys = ["Q", "W", "E", "R"];

        return new ChampionSpell(
            $spell['id'],
            $spell['name'] ?? "Missing Data",
            $keys[$spell['number']],
            $spell['image']['full'],
            $spell['maxrank'],
            $spell['description'] ?? "",
            str_replace('"', "'", $spell['tooltip'] ?? ''),
            $spell['cooldown'],
            $spell['range'],
            $spell['effect'] ?? [],
            $spell['vars'] ?? [],
            new ChampionSpellResource($spell['costType'] ?? '', $spell['cost'] ?? []),
            $spell['version'],
            $spell['region']
        );
    }

    /**
     * Fetch Champion spells
     *
     * @param  array $params API Parameters
     * @throws Exception
     */
    public function fetch(array $params): array
    {
        throw new \Exception("Fetch method for Champion Spells from API is not currently implemented");
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
