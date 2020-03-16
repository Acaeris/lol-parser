<?php

namespace App\Models\Champions;

use App\Models\Image;

class ChampionPassive
{
    /**
     * Passive ability name
     *
     * @var string
     */
    private $abilityName;

    /**
     * Passive ability image
     *
     * @var Image
     */
    private $image;

    /**
     * Ability description
     *
     * @var string
     */
    private $description;

    /**
     * Sanitised ability description
     *
     * @var string
     */
    private $sanitisedDescription;

    /**
     * @param string $abilityName - Ability Name
     * @param Image $image - Ability icon
     * @param string $description - Description
     * @param string $sanitisedDescription - Description without tags
     */
    public function __construct(
        string $abilityName,
        Image $image,
        string $description,
        string $sanitisedDescription
    ) {
        $this->abilityName = $abilityName;
        $this->image = $image;
        $this->description = $description;
        $this->sanitisedDescription = $sanitisedDescription;
    }

    public function getName(): string
    {
        return $this->abilityName;
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSanitisedDescription(): string
    {
        return $this->sanitisedDescription;
    }
}
