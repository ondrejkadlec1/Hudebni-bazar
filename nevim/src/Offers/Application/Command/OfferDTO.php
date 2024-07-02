<?php

namespace Ondra\App\Offers\Application\Command;

class CreateOfferDTO
{
    public string $name;
    public state
    public int $price;
    public int $quantity;
    public function __construct($product, $seller, $price, $createdAt = null, $updatedAt = null, $id = null, $quantity = 1)
    {
        $this->id = $id;
        $this->product = $product;
        $this->seller = $seller;
        $this->price = $price;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->quantity = $quantity;
    }
}