<?php
namespace Ondra\App\Offers\Domain;

use Nette\Utils\DateTime;

class Advert
{
    private string $id;
    private Item $item;
    private Seller $seller;
    private int $price;
    private int $quantity;
    public function __construct(string $id, Item $item, Seller $seller, int $price, int $quantity)
    {
        $this->id = $id;
        $this->item = $item;
        $this->seller = $seller;
        $this->price = $price;
        $this->quantity = $quantity;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getItem(): Item
    {
        return $this->item;
    }
    public function getSeller(): Seller
    {
        return $this->seller;
    }
    public function getPrice(): int
    {
        return $this->price;
    }
    public function setPrice($price): void
    {
        $this->price = $price;
    }
    public function getQuantity(): int
    {
        return $this->quantity;
    }
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }
}