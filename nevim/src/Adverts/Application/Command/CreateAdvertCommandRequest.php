<?php

namespace Ondra\App\Adverts\Application\Command;

use Ondra\App\Shared\Application\Command\CommandRequest;

class CreateAdvertCommandRequest implements CommandRequest
{
    private AdvertDTO $dto;

    public function __construct(string $name, int $stateId, int $price, int $subsubcategoryId, int $quantity = 1, string $details = "", array $images = [])
    {
        $this->dto = new AdvertDTO($name, $stateId, $price, $quantity, $details, $images, $subsubcategoryId);
    }
    public function getDto(): AdvertDTO
    {
        return $this->dto;
    }

}