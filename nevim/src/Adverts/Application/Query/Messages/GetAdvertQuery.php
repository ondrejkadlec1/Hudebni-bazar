<?php

namespace Ondra\App\Adverts\Application\Query;

use Ondra\App\Adverts\Domain\Advert;
use Ondra\App\Shared\Application\Query\Query;

class GetAdvertQuery implements Query
{
    private int $id;

    /**
     * @param int $id
     */

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}