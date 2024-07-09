<?php

namespace Ondra\App\Adverts\Application\Query\Messages;

use Ondra\App\Shared\Application\Query\Query;

class GetItemImageQuery implements Query
{
    private string $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}