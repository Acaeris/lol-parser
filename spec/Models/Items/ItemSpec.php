<?php

namespace spec\App\Models\Items;

use App\Models\Stat;
use PhpSpec\ObjectBehavior;
use App\Models\Items\ItemGold;
use App\Models\Image;

class ItemSpec extends ObjectBehavior
{
    private $itemId = 1;
    private $itemName = 'Infinity Edge';
    private $colloq = 'IE';
    private $description = 'Test description';
    private $sanitizedDescription = 'Sanitized description';
    private $tags = ['Attack'];
    private $plainText = 'Plaintext';
    private $stats = [];
    private $effects = ['effect'];
    private $hideFromAll = false;
    private $inStore = true;
    private $consumeOnFull = false;
    private $consumed = true;
    private $into = ['IE doesn`t but test'];
    private $from = ['Big Sword Thing'];
    private $maps = ['ARAM' => true];
    private $specialRecipe = 2;
    private $requiredChampion = 'Annie';
    private $group = 'Group';
    private $depth = 3;
    private $stacks = 4;

    public function let(
        ItemGold $gold,
        Stat $stat,
        Image $icon
    ) {
        $this->stats = [$stat];

        $this->beConstructedWith(
            $this->itemId,
            $this->itemName,
            $this->colloq,
            $icon,
            $this->description,
            $this->sanitizedDescription,
            $this->tags,
            $this->plainText,
            $this->stats,
            $gold,
            $this->effects,
            $this->hideFromAll,
            $this->inStore,
            $this->consumeOnFull,
            $this->consumed,
            $this->into,
            $this->from,
            $this->maps,
            $this->specialRecipe,
            $this->requiredChampion,
            $this->group,
            $this->depth,
            $this->stacks
        );
    }

    public function it_has_a_data_key()
    {
        $this->getKeyData()->shouldReturn([
            'item_id' => $this->itemId
        ]);
    }

    public function it_has_an_item_id()
    {
        $this->getItemID()->shouldReturn($this->itemId);
    }

    public function it_has_an_item_name()
    {
        $this->getItemName()->shouldReturn($this->itemName);
    }

    public function it_has_a_colloquial_name()
    {
        $this->getColloquialName()->shouldReturn($this->colloq);
    }

    public function it_has_an_icon(Image $icon)
    {
        $this->getImage()->shouldReturn($icon);
    }

    public function it_has_a_description()
    {
        $this->getDescription()->shouldReturn($this->description);
    }

    public function it_has_a_sanitized_description()
    {
        $this->getSanitizedDescription()->shouldReturn($this->sanitizedDescription);
    }

    public function it_has_tags()
    {
        $this->getTags()->shouldReturn($this->tags);
    }

    public function it_has_plain_text()
    {
        $this->getPlainText()->shouldReturn($this->plainText);
    }

    public function it_has_stats()
    {
        $this->getStats()->shouldReturn($this->stats);
    }

    public function it_has_gold_values(ItemGold $gold)
    {
        $this->getGold()->shouldReturn($gold);
    }

    public function it_has_effects()
    {
        $this->getEffects()->shouldReturn($this->effects);
    }

    public function it_has_if_hidden_From_all()
    {
        $this->isHiddenFromAll()->shouldReturn($this->hideFromAll);
    }

    public function it_has_if_in_store()
    {
        $this->isInStore()->shouldReturn($this->inStore);
    }

    public function it_has_if_consume_on_full()
    {
        $this->isConsumeOnFull()->shouldReturn($this->consumeOnFull);
    }

    public function it_has_if_consumed()
    {
        $this->isConsumed()->shouldReturn($this->consumed);
    }

    public function it_has_items_it_builds_into()
    {
        $this->getInto()->shouldReturn($this->into);
    }

    public function it_has_items_it_builds_from()
    {
        $this->getFrom()->shouldReturn($this->from);
    }

    public function it_has_what_maps_its_available_on()
    {
        $this->getMaps()->shouldReturn($this->maps);
    }

    public function it_has_if_it_is_a_special_recipe()
    {
        $this->getSpecialRecipe()->shouldReturn($this->specialRecipe);
    }

    public function it_has_a_required_champion()
    {
        $this->getRequiredChampion()->shouldReturn($this->requiredChampion);
    }

    public function it_has_a_group()
    {
        $this->getGroup()->shouldReturn($this->group);
    }

    public function it_has_a_depth()
    {
        $this->getDepth()->shouldReturn($this->depth);
    }

    public function it_has_stacks()
    {
        $this->getStacks()->shouldReturn($this->stacks);
    }
}
