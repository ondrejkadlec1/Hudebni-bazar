<?php

namespace Ondra\App\Offers\Application\Command;

class OfferDTO
{
    public string $name;
    public int $stateId;
    public string $details;
    public int $price;
    public int $quantity;
    public array $images;

    /**
     * @param string $name
     * @param int $stateId
     * @param string $details
     * @param int $price
     * @param int $quantity
     */
    public function __construct(string $name, int $stateId, int $price, int $quantity, string $details, array $images)
    {
        $this->name = $name;
        $this->stateId = $stateId;
        $this->details = $details;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->images = $images;
    }

}