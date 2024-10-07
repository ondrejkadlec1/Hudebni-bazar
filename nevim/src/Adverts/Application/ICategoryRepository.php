<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application;

interface ICategoryRepository
{
	public function getCategories(): array;
	public function getSubcategories(): array;
	public function getSubsubcategories(): array;
	public function getCategoryName(int $id): ?string;
	public function getSubcategoryName(int $id): ?string;
	public function getSubsubcategoryName(int $id): ?string;
}
