<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Infrastructure;

use Nette\Database\Connection;
use Nette\Database\Explorer;
use Ondra\App\Adverts\Application\IAdvertWriteRepository;
use Ondra\App\Adverts\Domain\Advert;
use Ondra\App\Adverts\Domain\Item;
use Ondra\App\Adverts\Domain\Seller;
use Ondra\App\Adverts\Domain\ItemImage;

final class DatabaseAdvertWriteRepository implements IAdvertWriteRepository
{
	public function __construct(
        private readonly Explorer $explorer,
        private readonly Connection $connection)
	{
	}

	public function getById(string $id): ?Advert
	{
		$data = $this->explorer->fetch("SELECT adverts.*, name, state_id, details, brand, subsubcategory_id, items.id AS item_id
            FROM adverts LEFT JOIN items ON adverts.id = items.id WHERE adverts.id = ?", $id);
		if (isset($data)) {
			$imagesData = $this->explorer->table('item_images')->where('item_id = ?', $data->item_id)->order('rank ASC');

			$images = [];
			if ($imagesData) {
				foreach ($imagesData as $imData) {
					$images[] = new ItemImage($imData->id);
				}
			}

            $item = new Item(
                $data->item_id,
                $data->name,
                $data->details,
                $data->state_id,
                $images,
                $data->subsubcategory_id,
                $data->brand,
            );

            $seller = new Seller(
                $data->seller_id,
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
        $this->connection->beginTransaction();
        if ($this->explorer->fetch("SELECT id FROM adverts WHERE id = ?", $advert->getId())) {
            $itemQuery = "UPDATE items SET name = ?, state_id = ?, details = ?, subsubcategory_id = ?, brand = ? WHERE id = ?";
            $advertQuery = "UPDATE adverts SET price = ?, quantity = ?, updated_at = ? WHERE id = ?";
            $this->connection->query($advertQuery, $advert->getPrice(), $advert->getQuantity(), $date(), $advert->getId());
            $this->connection->query(
                $itemQuery,
                $item->getName(),
                $item->getStateId(),
                $item->getDetails(),
                $item->getSubsubcategoryId(),
                $item->getBrand(),
                $item->getId(),
            );
        }
        else {
            $itemQuery = "INSERT INTO items (id, name, state_id, details, subsubcategory_id, brand) values(?, ?, ?, ?, ?, ?)";
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
        }
        $newImagesData = [];
        $oldImagesData = [];
        $shouldExist = [-1];
        foreach ($item->getItemImages() as $rank => $image) {
            if ($image->getFile() != null) {
                $extension = $image->getFile()->getImageFileExtension();
                $image->getFile()->move($_ENV['ITEM_IMAGES_DIRECTORY'] . $item->getId() . "_" . $image->getId() . "." . $extension);
                $newImagesData[] = ['id' => $image->getId(), 'item_id' => $item->getId(), 'rank' => $rank, 'extension' => $extension, 'created_at' => $date()];
            }
            else {
                $oldImagesData[] = ['id' => $image->getId(), 'item_id' => $item->getId(), 'rank' => $rank];
            }
            $shouldExist[] = $image->getId();
        }
        $toDelete = $this->connection->query("SELECT id, extension FROM item_images WHERE item_id = ? AND id NOT IN ?", $item->getId(), $shouldExist);
        $this->connection->query("DELETE FROM item_images WHERE item_id = ? AND id NOT IN ?", $item->getId(), $shouldExist);
        foreach ($toDelete as $row) {
            unlink($_ENV['ITEM_IMAGES_DIRECTORY'] . $item->getId() . "_" . $row->id . "." . $row->extension);
        }
        if (!empty($newImagesData)) {
            $this->connection->query("INSERT INTO item_images ? ON CONFLICT (item_id, id) DO UPDATE SET rank=excluded.rank, extension=excluded.extension, created_at=excluded.created_at", $newImagesData);
        }
        if (!empty($oldImagesData)) {
            $this->connection->query("INSERT INTO item_images ? ON CONFLICT (item_id, id) DO UPDATE SET rank=excluded.rank", $oldImagesData);
        }
        $this->connection->commit();
	}
	public function delete(string $id): void
	{
        $this->connection->beginTransaction();
        $toDelete = $this->connection->query("SELECT id, extension FROM item_images WHERE item_id = ?", $item->getId());
        $this->connection->query("DELETE FROM item_images WHERE item_id = ?", $id);
        $this->connection->query("DELETE FROM adverts WHERE id = ?", $id);
        $this->connection->query("DELETE FROM items WHERE id = ?", $id);
        $this->connection->commit();
        foreach ($toDelete as $row) {
            unlink($_ENV['ITEM_IMAGES_DIRECTORY'] . $id . "_" . $row->id . "." . $row->extension);
        }
	}
}
