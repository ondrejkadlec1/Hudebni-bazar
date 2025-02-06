<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Infrastructure;

use Nette\Database\Connection;
use Nette\Database\Explorer;
use Nette\Database\ResultSet;
use Nette\Database\Row;
use Ondra\App\Adverts\Application\IAuxiliaryRepository;

final class DatabaseAuxiliaryRepository implements IAuxiliaryRepository
{
    public function __construct(private readonly Explorer $explorer, private readonly Connection $connection)
    {
    }

    public function getCategories(): array
    {
        return $this->getSubordinateCategories();
    }

    public function getCategoryName(int $id): ?string
    {
        return $this->explorer->table('categories')->where('id', $id)->select('name')->fetch()?->name;
    }

    public function getStates(): array
    {
        return $this->explorer->table('states')->order('id')->fetchPairs('id', 'name');
    }

    public function getSubcategories(): array
    {
        $rows = $this->connection->query("SELECT id, name, sc1.higher_id as superordinate
            FROM categories
            LEFT OUTER JOIN superordinate_category AS sc1 ON id = sc1.lower_id
            LEFT OUTER JOIN superordinate_category AS sc2 ON sc1.higher_id = sc2.lower_id
            WHERE sc1.higher_id IS NOT NULL AND sc2.higher_id IS NULL");
        $result = [];
        foreach ($rows as $row) {
            $result[$row->superordinate][$row->id] = $row->name;
        }
        foreach (array_keys($this->getCategories()) as $categoryId) {
            if (!array_key_exists($categoryId, $result)) {
                $result[$categoryId] = [];
            }
        }
        return $result;
    }

    public function getSubordinateCategories(?int $superordinateId = null): array
    {
        $rows = $this->subordinateOrHighestCategory($superordinateId);
        $result = [];
        foreach ($rows as $row) {
            $result[$row->id] = $row->name;
        }
        return $result;
    }

    public function getSubsubcategories(): array
    {
        $rows = $this->connection->query("SELECT id, name, sc1.higher_id as superordinate
            FROM categories
            LEFT OUTER JOIN superordinate_category AS sc1 ON id = sc1.lower_id
            LEFT OUTER JOIN superordinate_category AS sc2 ON sc1.higher_id = sc2.lower_id
            WHERE sc2.higher_id IS NOT NULL");
        $result = [];
        foreach ($rows as $row) {
            $result[$row->superordinate][$row->id] = $row->name;
        }
        foreach ($this->getSubcategories() as $subcategories) {
            foreach ($subcategories as $subcategoryId => $_) {
                if (!array_key_exists($subcategoryId, $result)) {
                    $result[$subcategoryId] = [];
                }
            }
        }
        return $result;
    }

    private function subordinateOrHighestCategory(?int $superordinateId): ResultSet
    {
        if ($superordinateId !== null) {
            return $this->connection->query(
                "SELECT id, name FROM categories LEFT JOIN superordinate_category ON id = lower_id WHERE higher_id = ?",
                $superordinateId,
            );
        }
        return $this->connection->query(
            "SELECT id, name FROM categories LEFT OUTER JOIN superordinate_category ON id = lower_id WHERE higher_id IS NULL",
        );
    }
}
