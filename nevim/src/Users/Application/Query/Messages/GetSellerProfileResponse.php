<?php

namespace Ondra\App\Adverts\Application\Query\Messages;

use Ondra\App\Adverts\Application\Query\DTOs\SellerProfileDTO;
use Ondra\App\Shared\Application\Query\Query;

class GetSellerProfileResponse implements Query
{
    private SellerProfileDTO $dto;

    /**
     * @param SellerProfileDTO $dto
     */
    public function __construct(SellerProfileDTO $dto)
    {
        $this->dto = $dto;
    }

    /**
     * @return SellerProfileDTO
     */
    public function getDto(): SellerProfileDTO
    {
        return $this->dto;
    }
}