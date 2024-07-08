<?php

namespace Ondra\App\Offers\Domain;

class Seller
{
    private int $id;
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}