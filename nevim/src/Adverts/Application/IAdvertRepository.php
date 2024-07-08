<?php

namespace Ondra\App\Offers\Application;

use Ondra\App\Offers\Domain\Advert;

interface IAdvertRepository
{
    public function getById(int $id): Advert;
    public function getAll(): array;
    public function save(Advert $advert): void;
}