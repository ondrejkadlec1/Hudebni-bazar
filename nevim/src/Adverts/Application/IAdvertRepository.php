<?php

namespace Ondra\App\Adverts\Application;

use Ondra\App\Adverts\Application\Query\DTOs\AdvertDetailDTO;
use Ondra\App\Adverts\Domain\Advert;
interface IAdvertRepository
{
    public function getById(int $id): Advert;
    public function getDetail(int $id): AdvertDetailDTO;
    public function getOverviews($criteria): array;
    public function save(Advert $advert): void;
}