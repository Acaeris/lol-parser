<?php

namespace spec\App\Models\Champions;

use PhpSpec\ObjectBehavior;
use App\Models\Image;
use App\Models\Champions\ChampionInfo;
use App\Models\Champions\ChampionStats;
use App\Models\Champions\ChampionSkin;
use App\Models\Champions\ChampionPassive;
use App\Models\Champions\ChampionSpell;
use App\Models\Champions\ChampionRecommended;

class ChampionSpec extends ObjectBehavior
{
    public function it_has_all_core_parts(
        Image $image,
        ChampionInfo $info,
        ChampionStats $stats,
        ChampionSkin $skin,
        ChampionPassive $passive,
        ChampionSpell $spell,
        ChampionRecommended $recommended
    ) {
        $championId = 1;
        $championName = 'Annie';
        $championTitle = 'Test Title';
        $tags = ['Mage'];
        $partype = 'Mana';
        $skins = [$skin];
        $spells = [$spell];
        $recommendations = [$recommended];
        $tips = ['Test Tip'];
        $key = 'Ermm';
        $lore = 'Bad stuff happened';
        $blurb = 'Blahhh';

        $this->beConstructedWith(
            $championId,
            $championName,
            $championTitle,
            $image,
            $info,
            $stats,
            $tags,
            $partype,
            $skins,
            $passive,
            $spells,
            $recommendations,
            $tips,
            $key,
            $lore,
            $blurb
        );

        $this->getId()->shouldReturn($championId);
        $this->getName()->shouldReturn($championName);
        $this->getTitle()->shouldReturn($championTitle);
        $this->getImage()->shouldReturn($image);
        $this->getInfo()->shouldReturn($info);
        $this->getStats()->shouldReturn($stats);
        $this->getTags()->shouldReturn($tags);
        $this->getPartype()->shouldReturn($partype);
        $this->getSkins()->shouldReturn($skins);
        $this->getPassive()->shouldReturn($passive);
        $this->getSpells()->shouldReturn($spells);
        $this->getRecommendations()->shouldReturn($recommendations);
        $this->getTips()->shouldReturn($tips);
        $this->getKey()->shouldReturn($key);
        $this->getLore()->shouldReturn($lore);
        $this->getBlurb()->shouldReturn($blurb);
    }
}
