<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Infrastructure;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;
use Ondra\App\Adverts\Application\IAdvertReadRepository;
use Ondra\App\Adverts\Application\Query\DTOs\AdvertDetailDTO;
use Ondra\App\Adverts\Application\Query\DTOs\AdvertOverviewDTO;
use Ondra\App\Adverts\Application\Query\DTOs\SearchCriteria;

final class DatabaseAdvertReadRepository implements IAdvertReadRepository
{
	public function __construct(private readonly Explorer $explorer)
	{
	}

	public function getDetail(string $id): ?AdvertDetailDTO
	{
		$advertData = $this->explorer->fetch("SELECT * from adverts WHERE id = ?", $id);
		if (isset($advertData)) {
			$itemData = $this->explorer->fetch("SELECT * from items WHERE id = ?", $advertData->item_id);

			$subsubcategoryData = $this->explorer->fetch(
				"SELECT name, subcategory_id FROM subsubcategories WHERE id = ?",
				$itemData ?->subsubcategory_id,
			);
			$subcategoryData = $this->explorer->fetch(
				"SELECT name, category_id FROM subcategories WHERE id = ?",
				$subsubcategoryData ?->subcategory_id,
			);
			$categoryName = $this->explorer->fetch(
				"SELECT name FROM subsubcategories WHERE id = ?",
				$itemData ?->subsubcategory_id,
			) ?->name;

			$imagesData = $this->explorer->table('item_images')->select('id, extension')->where(
				'item_id = ?',
				$advertData->item_id,
			)->order(
				'created_at ASC',
			);
			$sellerUsername = $this->explorer->fetch(
				"SELECT username from users WHERE id = ?",
				$advertData->seller_id,
			) ?->username;
			$state = $this->explorer->fetch("SELECT name from states WHERE id = ?", $itemData->state_id) ?->name;

			$imageNames = [];
			foreach ($imagesData as $image) {
				$imageNames[] = $image->id . '.' . $image->extension;
			}

			$mainImageName = null;
			if (isset($imageNames[0])) {
				$mainImageName = $imageNames[0];
				unset($imageNames[0]);
			}
            if (isset($advertData, $itemData, $state, $categoryName, $subsubcategoryData, $subcategoryData)) {
                return new AdvertDetailDTO(
                    $advertData->id,
                    $itemData->name,
                    $advertData->price,
                    $advertData->quantity,
                    $itemData->state_id,
                    $state,
                    $sellerUsername,
                    $advertData->seller_id,
                    $itemData->details,
                    (string) $advertData->created_at,
                    $imageNames,
                    $subcategoryData->category_id,
                    $subsubcategoryData->subcategory_id,
                    $itemData->subsubcategory_id,
                    $categoryName,
                    $subcategoryData->name,
                    $subsubcategoryData->name,
                    $itemData->brand,
                    (string) $advertData->updated_at,
                    $mainImageName,
                );
            }
		}
		return null;
	}
	public function prepareSelection(SearchCriteria $criteria): Selection
	{
		$itemWhere = [];
		$advertWhere = [];

		if ($criteria->stateIds) {
			$itemWhere["state_id"] = $criteria->stateIds;
		}
		if ($criteria->brands) {
			$itemWhere["brand"] = $criteria->brands;
		}
		if ($criteria->subsubcategoryId) {
			$itemWhere["subsubcategory_id"] = $criteria->subsubcategoryId;
		} elseif ($criteria->subcategoryId) {
			$itemWhere["subsubcategory_id"] = $this->explorer->table('subsubcategories')->where(
				'subcategory_id',
				$criteria->subcategoryId,
			)->select(
				'id',
			);
		} elseif ($criteria->categoryId) {
			$subcategories = $this->explorer->table('subcategories')->where('category_id', $criteria->categoryId)->select(
				'id',
			);
			$itemWhere["subsubcategory_id"] = $this->explorer->table('subsubcategories')->where(
				'subcategory_id',
				$subcategories,
			)->select(
				'id',
			);
		}
		if ($criteria->sellerId) {
			$advertWhere["seller_id"] = $criteria->sellerId;
		}
		if ($criteria->minPrice) {
			$advertWhere["price >="] = $criteria->minPrice;
		}
		if ($criteria->maxPrice) {
			$advertWhere["price <="] = $criteria->maxPrice;
		}

		$itemQuery = $this->explorer->table('items');

		if ($criteria->expression) {
			$words = explode(" ", $criteria->expression);
			foreach ($words as $word) {
				$word = '%' . $word . '%';
				$itemQuery->where("name ILIKE ? OR details ILIKE ?", $word, $word);
			}
		}
		$advertWhere["item_id"] = $itemQuery->where($itemWhere)->select('id');
		return $this->explorer->table('adverts')->where($advertWhere);
	}
	public function processOrdering(SearchCriteria $criteria): string
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
		return $this->prepareSelection($criteria)->count();
	}
	public function getOverviews(SearchCriteria $criteria): array
	{
		$advertDataArray = $this->prepareSelection($criteria)->limit($criteria->limit, $criteria->offset)->order(
			$this->processOrdering($criteria),
		);

		$dtos = [];

		foreach ($advertDataArray as $advertData) {
			$itemData = $this->explorer->fetch("SELECT * from items WHERE id = ?", $advertData->item_id);
			$imageData = $this->explorer->fetch(
				"SELECT id, extension from item_images WHERE item_id = ? ORDER BY created_at ASC",
				$advertData->item_id,
			);

			if (! empty($imageData)) {
				$imageName = $imageData->id . '.' . $imageData->extension;
			} else {
				$imageName = null;
			}

			$sellerUsername = $this->explorer->fetch(
				"SELECT username from users WHERE id = ?",
				$advertData->seller_id,
			) ?->username;

			$subsubcategory = $this->explorer->fetch(
				"SELECT name FROM subsubcategories WHERE id = ?",
				$itemData->subsubcategory_id,
			) ?->name;
			$state = $this->explorer->fetch("SELECT name from states WHERE id = ?", $itemData->state_id) ?->name;

			$dtos[] = new AdvertOverviewDTO(
				$advertData->id,
				$itemData->name,
				$advertData->price,
				$state,
				$sellerUsername,
				$advertData->seller_id,
				$itemData->details,
				$subsubcategory,
                (string) $advertData->created_at,
				$itemData->brand,
                (string) $advertData->updated_at,
				$imageName,
			);
		}
		return $dtos;
	}
}
