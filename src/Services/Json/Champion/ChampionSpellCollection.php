<?php
namespace App\Services\Json\Champion;

use Psr\Log\LoggerInterface;
use App\Services\FetchServiceInterface;
use App\Adapters\AdapterInterface;
use App\Models\EntityInterface;
use App\Models\Champion\ChampionSpell;
use App\Models\Champion\ChampionSpellResource;

/**
 * Champion Spells object JSON factory
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class ChampionSpellCollection implements FetchServiceInterface
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
     * @return EntityInterface
     */
    public function create(array $spell): EntityInterface
    {
        $keys = ["Q", "W", "E", "R"];

        return new ChampionSpell(
            $spell['id'],
            isset($spell['name']) ? $spell['name'] : "Missing Data",
            $keys[$spell['number']],
            $spell['image']['full'],
            $spell['maxrank'],
            isset($spell['description']) ? $spell['description'] : "",
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
     * Fetch Champion spells
     *
     * @param array $params API Parameters
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
