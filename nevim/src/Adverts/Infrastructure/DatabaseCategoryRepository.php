<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Infrastructure;

use Nette\Database\Explorer;
use Ondra\App\Adverts\Application\ICategoryRepository;

final class DatabaseCategoryRepository implements ICategoryRepository
{
	public function __construct(private readonly Explorer $explorer)
	{
	}

	public function getCategories(): array
	{
		return $this->explorer->table('categories')->fetchPairs('id', 'name');
	}

	public function getSubcategories(): array
	{
        $subcategories = [];
		$categories = $this->explorer->table('categories')->select('id');
		foreach ($categories as $category) {
			$subcategories[$category->id] = $this->explorer->table('subcategories')->where(
				'category_id',
				$category->id,
			)->fetchPairs(
				'id',
				'name',
			);
		}
		return $subcategories;
	}

	public function getSubsubcategories(): array
	{
        $subsubcategories = [];
		$subcategories = $this->explorer->table('subcategories')->select('id');
		foreach ($subcategories as $subcategory) {
			$subsubcategories[$subcategory->id] = $this->explorer->table('subsubcategories')->where(
				'subcategory_id',
				$subcategory->id,
			)->fetchPairs(
				'id',
				'name',
			);
		}
		return $subsubcategories;
	}

	public function getCategoryName(int $id): ?string
	{
		return $this->explorer->table('categories')->where('id', $id)->select('name')->fetch() ?->name;
	}
	public function getSubcategoryName(int $id): ?string
	{
		return $this->explorer->table('subcategories')->where('id', $id)->select('name')->fetch() ?->name;
	}
	public function getSubsubcategoryName(int $id): ?string
	{
		return $this->explorer->table('subsubcategories')->where('id', $id)->select('name')->fetch() ?->name;
	}
}
