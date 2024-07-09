<?php

namespace Ondra\App\Adverts\Application\Query\Messages;
use Ondra\App\Shared\Application\Query\Query;

class GetAdvertsResponse implements Query
{
    private array $dtos;

    /**
     * @param array $dtos
     */
    public function __construct(array $dtos)
    {
        $this->dtos = $dtos;
    }

    /**
     * @return array
     */
    public function getDtos(): array
    {
        return $this->dtos;
    }
}