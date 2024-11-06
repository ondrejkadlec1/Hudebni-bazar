<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Infrastructure;

use Nette\Database\Connection;
use Nette\Database\Explorer;
use Ondra\App\Adverts\Application\ICategoryRepository;

final class DatabaseCategoryRepository implements ICategoryRepository
{
	public function __construct(private readonly Explorer $explorer, private readonly Connection $connection)
	{
	}

	public function getCategories(): array
	{
		return $this->explorer->table('categories')->fetchPairs('id', 'name');
	}

	public function getSubcategories(): array
	{
        $records = $this->connection->query("SELECT categories.id AS category,
            subcategories.id AS subcategory_id, subcategories.name subcategory_name 
            FROM categories LEFT JOIN subcategories ON categories.id = subcategories.category_id");
        $subcategories = [];
        foreach ($records as $record){
            if ($record->subcategory_id){
                $subcategories[$record->category][(int) $record->subcategory_id] = $record->subcategory_name;
            }
            else {
                $subcategories[$record->category] = [];
            }
        }
        return $subcategories;
	}

	public function getSubsubcategories(): array
	{
        $records = $this->connection->query("SELECT subcategories.id AS subcategory,
            subsubcategories.id AS subsubcategory_id, subsubcategories.name subsubcategory_name 
            FROM subcategories LEFT JOIN subsubcategories ON subcategories.id = subsubcategories.subcategory_id");
        $subsubcategories = [];
        foreach ($records as $record){
            if ($record->subsubcategory_id) {
                $subsubcategories[$record->subcategory][(int)$record->subsubcategory_id] = $record->subsubcategory_name;
            }
            else {
                $subsubcategories[$record->subcategory] = [];
            }
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
