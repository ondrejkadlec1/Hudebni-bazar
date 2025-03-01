<?php

namespace Ondra\App\Adverts\Domain;

use Generator;
use IteratorAggregate;

class ImageCollection implements IteratorAggregate
{
    private array $images;

    public function __construct(private readonly int $max)
    {
        $this->images = [];
    }

    public function add(ItemImage $image): void
    {
        if (count($this->images) <= $this->max) {
            $this->images[] = $image;
        }
    }

    public function clear(): void
    {
        $this->images = [];
    }

    public function getIterator(): Generator
    {
        foreach ($this->images as $image){
            yield $image;
        }
    }
}