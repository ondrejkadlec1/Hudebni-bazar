<?php

namespace Ondra\App\Adverts\Application\Query\Messages;

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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}