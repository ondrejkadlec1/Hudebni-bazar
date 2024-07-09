<?php

namespace Ondra\App\Adverts\Application\Query\Messages;

use Ondra\App\Adverts\Application\Query\DTOs\AdvertDetailDTO;
use Ondra\App\Adverts\Application\Query\DTOs\AdvertOverviewDTO;
use Ondra\App\Shared\Application\Query\Query;
class GetAdvertResponse implements Query
{
    private AdvertDetailDTO $dto;

    /**
     * @param AdvertDetailDTO $dto
     */
    public function __construct(AdvertDetailDTO $dto)
    {
        $this->dto = $dto;
    }

    /**
     * @return AdvertDetailDTO
     */
    public function getDto(): AdvertDetailDTO
    {
        return $this->dto;
    }
    public function getImageNames(): array
    {
        return $this->dto->getImageNames();
    }
}