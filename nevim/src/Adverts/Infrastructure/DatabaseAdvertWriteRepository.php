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
		$data = $this->explorer->fetch("SELECT adverts.*, name, state_id, details, brand, subsubcategory_id
            FROM adverts LEFT JOIN items ON adverts.id = items.id WHERE id = ?", $id);
		if (isset($data)) {
			$imagesData = $this->explorer->table('item_images')->where('item_id = ?', $data->item_id);

			$images = [];
			if ($imagesData) {
				foreach ($imagesData as $data) {
					$images[] = $data->id . '.' . $data->extension;
				}
			}

            $item = new Item(
                $data->id,
                $data->name,
                $data->details,
                $data->state_id,
                $images,
                $data->subsubcategory_id,
                $data->brand,
            );

            $seller = new Seller(
                $data->id,
            );

			return new Advert(
				$data->id,
				$item,
				$seller,
				$data->price,
				$data->quantity,
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
			$advertQuery = "INSERT INTO adverts (id, seller_id, price, quantity, created_at) values (?, ?, ?, ?, ?)";

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
		$itemId = $this->explorer->fetch('SELECT id FROM adverts WHERE id = ?', $id) ?->item_id;
		$this->connection->query("DELETE FROM item_images WHERE item_id = ?", $itemId);
		$this->connection->query("DELETE FROM adverts WHERE id = ?", $id);
		$this->connection->query("DELETE FROM items WHERE id = ?", $itemId);
	}
}
