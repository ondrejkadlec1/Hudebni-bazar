<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Infrastructure;

use Exception;
use Nette\Database\Connection;
use Nette\Database\Explorer;
use Ondra\App\Adverts\Application\IAdvertReadRepository;
use Ondra\App\Adverts\Application\Query\DTOs\AdvertDetailDTO;
use Ondra\App\Adverts\Application\Query\DTOs\AdvertOverviewDTO;
use Ondra\App\Adverts\Application\Query\DTOs\SearchCriteria;
use Ondra\App\Shared\Infrastructure\CET;

final class DatabaseAdvertReadRepository implements IAdvertReadRepository
{
    use CET;

    public function __construct(private readonly Explorer $explorer, private readonly Connection $connection)
    {
    }

    public function getCount(SearchCriteria $criteria): int
    {
        return $this->connection->fetch(sprintf('SELECT COUNT(adverts.id) AS advert_count FROM %s WHERE ?', $this->tableJoin()), $this->generateWhere($criteria))->advert_count;
    }

    public function getDetail(string $id): ?AdvertDetailDTO
    {
        $data = $this->connection->fetch(sprintf('SELECT adverts.*, 
                items.name AS name, state_id, details, brand, lowest_category_id, 
                states.name AS state, 
                lowest_category.name AS lowest_category_name, sc1.higher_id AS middle_category_id, 
                middle_category.name AS middle_category_name, sc2.higher_id AS upper_category_id,
                upper_category.name AS upper_category_name,
                rank AS rank, extension, image_id,
                username FROM %s WHERE adverts.id = ?', $this->tableJoin()), $id);

        if ($data !== null) {
            $imageNames = [];
            $imageIds = [];
            $mainImageName = null;
            if ($data->image_id !== null) {
                $mainImageName = sprintf('%s_%s.%s', $id, $data->image_id, $data->extension);

                $imagesData = $this->explorer->table('item_images')->select('rank, extension, id')->where(
                    'item_id',
                    $id,
                )->order('rank ASC');
                foreach ($imagesData as $image) {
                    $imageIds[] = $image->id;
                    $imageNames[] = sprintf('%s_%s.%s', $id, $image->id, $image->extension);
                }
            }
            $categories = [];
            if ($data->upper_category_id !== null) {
                $categories[$data->upper_category_id] = $data->upper_category_name;
            }
            if ($data->middle_category_id !== null) {
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
                $data->updated_at === null ? null : $this->toCET($data->updated_at),
                $mainImageName,
            );
        }
        return null;
    }

    public function getOverviews(SearchCriteria $criteria): array
    {
        $dataArray = $this->connection->query(
            sprintf('SELECT adverts.*, 
                items.name AS name, details, brand, 
                lowest_category.name AS subsubcategory_name, 
                states.name AS state, 
                extension, image_id,
                username 
                FROM %s WHERE ? ORDER BY %s LIMIT ? OFFSET ?', $this->tableJoin(), $this->generateOrderBy($criteria)),
            $this->generateWhere($criteria),
            $criteria->limit,
            $criteria->offset,
        );
        $dtos = [];

        foreach ($dataArray as $data) {
            $imageName = null;
            if ($data->image_id !== null) {
                $imageName = sprintf('%s_%s.%s', $data->id, $data->image_id, $data->extension);
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
                $data->updated_at === null ? null : $this->toCET($data->updated_at),
                $imageName,
            );
        }
        return $dtos;
    }

    private function generateOrderBy(SearchCriteria $criteria): string
    {
        $orderBy = match ($criteria->orderBy) {
            'date' => 'created_at',
            'price' => 'price',
            default => throw new Exception(
                sprintf('Invalid value in criteria order by: %s. Use predefined constants only.', $criteria->orderBy)
            ),
        };
        return match ($criteria->direction) {
            'asc' => $orderBy . ' ASC',
            'desc' => $orderBy . ' DESC',
            default => throw new Exception(
                sprintf('Invalid value in criteria direction: %s. Use predefined constants only.', $criteria->direction)
            ),
        };
    }

    private function generateWhere(SearchCriteria $criteria): array
    {
        $where = [];

        if ($criteria->stateIds) {
            $where["state_id"] = $criteria->stateIds;
        }
        if ($criteria->brands) {
            $where["brand"] = $criteria->brands;
        }
        if ($criteria->categoryId) {
            $where[] = $this->connection::literal(
                'lowest_category.id = ? OR middle_category.id = ? OR upper_category.id = ?',
                $criteria->categoryId,
                $criteria->categoryId,
                $criteria->categoryId,
            );
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

    private function tableJoin(): string
    {
        return 'adverts
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
    }
}
