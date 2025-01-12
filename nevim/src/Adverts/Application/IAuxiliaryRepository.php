<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application;

interface IAuxiliaryRepository
{
    public function getCategories(): array;
    public function getSubcategories(): array;
    public function getSubsubcategories(): array;
    public function getSubordinateCategories(?int $superordinateId = null): array;
	public function getCategoryName(int $id): ?string;
    public function getStates(): array;
}
