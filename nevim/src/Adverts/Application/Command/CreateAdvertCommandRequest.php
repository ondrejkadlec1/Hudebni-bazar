<?php

namespace Ondra\App\Offers\Application\Command;

use Ondra\App\Shared\Application\Command\CommandRequest;

class CreateOfferCommandRequest implements CommandRequest
{
    public OfferDTO $dto;

    /**
     * @param OfferDTO $dto
     */
    public function __construct(string $name, int $stateId, int $price, int $quantity = 1, string $details = "", array $images = [])
    {
        $this->dto = new OfferDTO($name, $stateId, $price, $quantity, $details, $images);
    }
}