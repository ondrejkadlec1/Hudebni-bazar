<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Infrastructure;

use Nette\Database\Explorer;
use Nette\Database\Connection;
use Ondra\App\Adverts\Application\IAdvertReadRepository;
use Ondra\App\Adverts\Application\Query\DTOs\AdvertDetailDTO;
use Ondra\App\Adverts\Application\Query\DTOs\AdvertOverviewDTO;
use Ondra\App\Adverts\Application\Query\DTOs\SearchCriteria;
use Ondra\App\Shared\Infrastructure\CET;

final class DatabaseAdvertReadRepository implements IAdvertReadRepository
{
    use CET;
    private const TABLE_JOIN = 'adverts 
                LEFT JOIN items ON adverts.id = items.id 
                LEFT JOIN states ON items.state_id = states.id
                LEFT JOIN users ON adverts.seller_id = users.id
                LEFT JOIN superordinate_category AS sc1 ON sc1.lower_id = lowest_category_id
                LEFT JOIN superordinate_category AS sc2 ON sc2.lower_id = sc1.higher_id
                LEFT JOIN categories AS lowest_category ON lowest_category.id = lowest_category_id
                LEFT JOIN categories AS middle_category ON middle_category.id = sc1.higher_id
                LEFT JOIN categories AS upper_category ON upper_category.id = sc2.higher_id
                LEFT JOIN (SELECT id AS image_id, extension, rank, item_id FROM item_images WHERE rank = 0) as i ON items.id = i.item_id
                ';
	public function __construct(private readonly Explorer $explorer, private readonly Connection $connection)
	{
	}
	public function getDetail(string $id): ?AdvertDetailDTO
    {
        $data = $this->connection->fetch("SELECT adverts.*, 
                items.name AS name, state_id, details, brand, lowest_category_id, 
                states.name AS state, 
                lowest_category.name AS lowest_category_name, sc1.higher_id AS middle_category_id, 
                middle_category.name AS middle_category_name, sc2.higher_id AS upper_category_id,
                upper_category.name AS upper_category_name,
                rank AS rank, extension, image_id,
                username FROM "
            . self::TABLE_JOIN .
            " WHERE adverts.id = ?", $id);

        if (isset($data)) {
            $imageNames = [];
            $imageIds = [];
            $mainImageName = null;
            if (isset($data->image_id)) {
                $mainImageName = $id . '_' . $data->image_id . '.' . $data->extension;

                $imagesData = $this->explorer->table('item_images')->select('rank, extension, id')->where(
                    'item_id', $id)->order('rank ASC');
                foreach ($imagesData as $image) {
                    $imageIds[] = $image->id;
                    $imageNames[] = $id . '_' . $image->id . '.' . $image->extension;
                }
            }
            if (isset($data->upper_category_id)) {
                $categories[$data->upper_category_id] = $data->upper_category_name;
            }
            if (isset($data->middle_category_id)) {
                $categories[$data->middle_category_id] = $data->middle_category_name;
            }
            $categories[$data->lowest_category_id] = $data->lowest_category_name;

                return new AdvertDetailDTO(
                    $data->id,
                    $data->name,
                    $data->price,
                    $data->quantity,
                    $data->state_id,
                    $data->state,
                    $data->username,
                    $data->seller_id,
                    $data->details,
                    $this->toCET($data->created_at),
                    $imageNames,
                    $imageIds,
                    $categories,
                    $data->brand,
                    $this->toCET($data->updated_at),
                    $mainImageName,
                );
        }
        return null;
    }
	public function generateWhere(SearchCriteria $criteria): array
	{
		$where = [];

        if ($criteria->stateIds) {
            $where["state_id"] = $criteria->stateIds;
        }
        if ($criteria->brands) {
            $where["brand"] = $criteria->brands;
        }
        if ($criteria->categoryId) {
            $where[] = $this->connection::literal('lowest_category.id = ? OR middle_category.id = ? OR upper_category.id = ?', $criteria->categoryId, $criteria->categoryId, $criteria->categoryId);
        }
        if ($criteria->sellerId) {
            $where["seller_id"] = $criteria->sellerId;
        }
        if ($criteria->minPrice) {
            $where["price >="] = $criteria->minPrice;
        }
        if ($criteria->maxPrice) {
            $where["price <="] = $criteria->maxPrice;
        }
        if ($criteria->expression) {
            $words = explode(" ", $criteria->expression);
            foreach ($words as $word) {
                $word = '%' . $word . '%';
                $where[] = $this->connection::literal('items.name ILIKE ? OR details ILIKE ?', $word, $word);
            }
        }
		return $where;
	}
	public function generateOrderBy(SearchCriteria $criteria): string
	{
		switch ($criteria->orderBy) {
			case 'date':
				$orderBy = 'created_at';
				break;
			case 'price':
				$orderBy = 'price';
				break;
			default:
				throw new \Exception(
					'Invalid value in criteria order by: ' . $criteria->orderBy . ". Use predefined constants only.",
				);
		}

		switch ($criteria->direction) {
			case 'asc':
				$orderBy = $orderBy . ' ASC';
				break;
			case 'desc':
				$orderBy = $orderBy . ' DESC';
				break;
			default:
				throw new \Exception(
					'Invalid value in criteria direction: ' . $criteria->direction . ". Use predefined constants only.",
				);
		}
		return $orderBy;
	}
	public function getCount(SearchCriteria $criteria): int
	{
		return $this->connection->fetch("SELECT COUNT(adverts.id) AS advert_count FROM " . self::TABLE_JOIN .
            " WHERE ?", $this->generateWhere($criteria))->advert_count;
	}
	public function getOverviews(SearchCriteria $criteria): array
	{
		$dataArray = $this->connection->query("SELECT adverts.*, 
                items.name AS name, details, brand, 
                lowest_category.name AS subsubcategory_name, 
                states.name AS state, 
                extension, image_id,
                username 
                FROM " . self::TABLE_JOIN .
                " WHERE ? ORDER BY " . $this->generateOrderBy($criteria) . " LIMIT ? OFFSET ?",
            $this->generateWhere($criteria), $criteria->limit, $criteria->offset
        );
		$dtos = [];

		foreach ($dataArray as $data) {
			if (isset($data->image_id)) {
				$imageName = $data->id . '_' . $data->image_id . '.' . $data->extension;
			} else {
				$imageName = null;
			}

			$dtos[] = new AdvertOverviewDTO(
				$data->id,
				$data->name,
				$data->price,
				$data->state,
				$data->username,
				$data->seller_id,
				$data->details,
				$data->subsubcategory_name,
                $this->toCET($data->created_at),
				$data->brand,
                $this->toCET($data->updated_at),
				$imageName,
			);
		}
		return $dtos;
	}
}
