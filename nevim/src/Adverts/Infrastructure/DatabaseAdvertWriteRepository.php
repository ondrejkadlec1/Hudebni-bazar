<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Infrastructure;

use Nette\Database\Connection;
use Nette\Database\Explorer;
use Ondra\App\Adverts\Application\IAdvertWriteRepository;
use Ondra\App\Adverts\Domain\Advert;
use Ondra\App\Adverts\Domain\Item;
use Ondra\App\Adverts\Domain\Seller;

final class DatabaseAdvertWriteRepository implements IAdvertWriteRepository
{
	public function __construct(
        private readonly Explorer $explorer,
        private readonly Connection $connection)
	{
	}

	public function getById(string $id): ?Advert
	{
		$advertData = $this->explorer->fetch("SELECT * from adverts WHERE id = ?", $id);
		if (isset($advertData)) {
			$itemData = $this->explorer->fetch("SELECT * from items WHERE id = ?", $advertData->item_id);
			$imagesData = $this->explorer->table('item_images')->where('item_id = ?', $advertData->item_id);
			$sellerData = $this->explorer->fetch("SELECT id, username FROM users WHERE id = ?", $advertData->seller_id);

			$images = [];
			if ($imagesData) {
				foreach ($imagesData as $data) {
					$images[] = $data->id . '.' . $data->extension;
				}
			}

            if (isset($itemData)) {
                $item = new Item(
                    $itemData->id,
                    $itemData->name,
                    $itemData->details,
                    $itemData->state_id,
                    $images,
                    $itemData->subsubcategory_id,
                    $itemData->brand,
                );
            }

            if (isset($sellerData)) {
                $seller = new Seller(
                    $sellerData->id,
                );
            }

			return new Advert(
				$advertData->id,
				$item,
				$seller,
				$advertData->price,
				$advertData->quantity,
			);
		}
		return null;
	}
	public function save(Advert $advert): void
	{
		$item = $advert->getItem();
		$seller = $advert->getSeller();
		$date = function () {
			return date(DATE_ATOM);
		};

		if ($this->explorer->fetch("SELECT id FROM adverts WHERE id = ?", $advert->getId())) {
			$itemQuery = "UPDATE items SET name = ?, state_id = ?, details = ?, subsubcategory_id = ?, brand = ? WHERE id = ?";
			$advertQuery = "UPDATE adverts SET price = ?, quantity = ?, updated_at = ? WHERE id = ?";

			$this->connection->query(
				$itemQuery,
				$item->getName(),
				$item->getStateId(),
				$item->getDetails(),
				$item->getSubsubcategoryId(),
				$item->getBrand(),
				$item->getId(),
			);
			$this->connection->query($advertQuery, $advert->getPrice(), $advert->getQuantity(), $date(), $advert->getId());
		} else {
			$itemQuery = "INSERT INTO items (id, name, state_id, details, subsubcategory_id, brand) values(?, ?, ?, ?, ?, ?)";
			$itemImagesQuery = "INSERT INTO item_images (id, item_id, extension, created_at) values (?, ?, ?, ?)";
			$advertQuery = "INSERT INTO adverts (item_id, seller_id, price, quantity, created_at) values (?, ?, ?, ?, ?)";

			$this->connection->query(
				$itemQuery,
				$item->getId(),
				$item->getName(),
				$item->getStateId(),
				$item->getDetails(),
				$item->getSubsubcategoryId(),
				$item->getBrand(),
			);
			$this->connection->query(
				$advertQuery,
				$item->getId(),
				$seller->getId(),
				$advert->getPrice(),
				$advert->getQuantity(),
				$date(),
			);
			foreach ($item->getItemImages() as $image) {
				$this->connection->query($itemImagesQuery, $image->getId(), $item->getId(), $image->getExtension(), $date());
			}
		}
	}
	public function delete(string $id): void
	{
		$itemId = $this->explorer->fetch('SELECT item_id FROM adverts WHERE id = ?', $id) ?->item_id;
		$this->connection->query("DELETE FROM item_images WHERE item_id = ?", $itemId);
		$this->connection->query("DELETE FROM adverts WHERE id = ?", $id);
		$this->connection->query("DELETE FROM items WHERE id = ?", $itemId);
	}
}
