<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Domain;

final class Item
{
    private ImageCollection $itemImages;

    public function __construct(
        private string       $name,
        private string       $details,
        private int          $stateId,
        private int          $lowestCategoryId,
        private readonly int $maxImages,
        private ?string      $brand = null,
    )
    {
        $this->itemImages = new ImageCollection($this->maxImages);
    }

    public function clearImages(): void
    {
        $this->itemImages->clear();
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function getItemImages(): ImageCollection
    {
        return $this->itemImages;
    }

    public function getLowestCategoryId(): int
    {
        return $this->lowestCategoryId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStateId(): int
    {
        return $this->stateId;
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function setDetails(string $details): void
    {
        $this->details = $details;
    }

    public function setImages(array $images): void
    {
        $this->itemImages->clear();
        foreach ($images as $image){
            $this->itemImages->add($image);
        }
    }

    public function setLowestCategoryId(int $lowestCategoryId): void
    {
        $this->lowestCategoryId = $lowestCategoryId;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setStateId(int $stateId): void
    {
        $this->stateId = $stateId;
    }
}
