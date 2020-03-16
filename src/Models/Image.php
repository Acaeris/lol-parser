<?php

namespace App\Models;

class Image
{
    /* @var string Full image path */
    private $fullPath;

    /* @var string Group */
    private $group;

    /* @var string Spritesheet path */
    private $spritePath;

    /* @var int Image height */
    private $height;

    /* @var int Image width */
    private $width;

    /* @var int Spritesheet X position */
    private $xPos;

    /* @var int Spritesheet Y position */
    private $yPos;

    public function __construct(
        string $fullPath,
        string $group,
        string $spritePath,
        int $height,
        int $width,
        int $xPos,
        int $yPos
    ) {
        $this->fullPath = $fullPath;
        $this->group = $group;
        $this->spritePath = $spritePath;
        $this->height = $height;
        $this->width = $width;
        $this->xPos = $xPos;
        $this->yPos = $yPos;
    }

    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function getSpritePath(): string
    {
        return $this->spritePath;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getXPos(): int
    {
        return $this->xPos;
    }

    public function getYPos(): int
    {
        return $this->yPos;
    }
}
