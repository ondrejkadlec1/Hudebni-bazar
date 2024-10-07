<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application;

use Ondra\App\Adverts\Domain\Advert;

interface IAdvertWriteRepository
{
	public function getById(string $id): ?Advert;
	public function save(Advert $advert): void;
	public function delete(string $id): void;
}
