<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Domain;

final class Advert
{
    public function __construct(
        private readonly string $id,
        private readonly Item   $item,
        private readonly Seller $seller,
        private int             $price,
        private int             $quantity,
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getItemBrand(): ?string
    {
        return $this->item->getBrand();
    }

    public function getItemDetails(): string
    {
        return $this->item->getDetails();
    }

    public function getItemImages(): array
    {
        return $this->item->getItemImages();
    }

    public function getItemLowestCategoryId(): int
    {
        return $this->item->getLowestCategoryId();
    }

    public function getItemName(): string
    {
        return $this->item->getName();
    }

    public function getItemStateId(): int
    {
        return $this->item->getStateId();
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getSellerId(): string
    {
        return $this->seller->getId();
    }

    public function setItemBrand(string $brand): void
    {
        $this->item->setBrand($brand);
    }

    public function setItemDetails(string $details): void
    {
        $this->item->setDetails($details);
    }

    public function setItemImages(array $images): void
    {
        $this->item->setItemImages($images);
    }

    public function setItemLowestCategoryId(int $categoryId): void
    {
        $this->item->setLowestCategoryId($categoryId);
    }

    public function setItemName(string $name): void
    {
        $this->item->setName($name);
    }

    public function setItemStateId(int $stateId): void
    {
        $this->item->setStateId($stateId);
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
