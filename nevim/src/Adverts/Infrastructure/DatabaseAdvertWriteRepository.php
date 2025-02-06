<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Infrastructure;

use Exception;
use Nette\Database\Connection;
use Nette\Database\Explorer;
use Ondra\App\Adverts\Application\IAdvertWriteRepository;
use Ondra\App\Adverts\Domain\Advert;
use Ondra\App\Adverts\Domain\Item;
use Ondra\App\Adverts\Domain\ItemImage;
use Ondra\App\Adverts\Domain\Seller;
use Ondra\App\ApplicationConfiguration;

final class DatabaseAdvertWriteRepository implements IAdvertWriteRepository
{
    public function __construct(
        private readonly Explorer   $explorer,
        private readonly Connection $connection,
        private readonly ApplicationConfiguration $configuration
    )
    {
    }

    public function delete(string $id): void
    {
        $this->connection->beginTransaction();
        try {
            $toDelete = $this->connection->query("SELECT id, extension FROM item_images WHERE item_id = ?", $id);
            $this->connection->query("DELETE FROM item_images WHERE item_id = ?", $id);
            $this->connection->query("DELETE FROM adverts WHERE id = ?", $id);
            $this->connection->query("DELETE FROM items WHERE id = ?", $id);
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
        }
        foreach ($toDelete as $row) {
            unlink(sprintf("%s%s_%s.%s", $this->configuration->get()['itemImagesDirectory'], $id, $row->id, $row->extension));
        }
    }

    public function getById(string $id): ?Advert
    {
        $data = $this->explorer->fetch(
            "SELECT adverts.*, name, state_id, details, brand, lowest_category_id, items.id AS item_id
            FROM adverts LEFT JOIN items ON adverts.id = items.id WHERE adverts.id = ?",
            $id,
        );
        if ($data !== null) {
            $imagesData = $this->explorer->table('item_images')->where('item_id = ?', $data->item_id)->order('rank ASC');

            $images = [];
            if ($imagesData !== null) {
                foreach ($imagesData as $imData) {
                    $images[] = new ItemImage($imData->id);
                }
            }

            $item = new Item(
                $data->name,
                $data->details,
                $data->state_id,
                $images,
                $data->lowest_category_id,
                $this->configuration->get()['imagesPerItem'],
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
        $this->connection->beginTransaction();
        try {
            $this->saveBasicToDB($advert);
            $newImagesData = [];
            $oldImagesData = [];
            $shouldExist = [-1];
            foreach ($advert->getItemImages() as $rank => $image) {
                $shouldExist[] = $image->getId();
                if ($image->getFile() !== null) {
                    $newImagesData[] = [
                        'id' => $image->getId(),
                        'item_id' => $advert->getId(),
                        'rank' => $rank,
                        'extension' => $image->getFileExtension(),
                        'created_at' => $this->date(),
                    ];
                    continue;
                }
                $oldImagesData[] = [
                    'id' => $image->getId(),
                    'item_id' => $advert->getId(),
                    'rank' => $rank,
                ];
            }
            $this->saveImagesToDB($oldImagesData, $newImagesData, $shouldExist, $advert->getId());
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
        $this->saveImagesToFolder($advert, $shouldExist);
    }

    private function date(): string
    {
        return date(DATE_ATOM);
    }

    private function saveBasicToDB(Advert $advert): void
    {
        if ($this->explorer->fetch("SELECT id FROM adverts WHERE id = ?", $advert->getId()) !== null) {
            $itemQuery = "UPDATE items SET name = ?, state_id = ?, details = ?, lowest_category_id = ?, brand = ? WHERE id = ?";
            $advertQuery = "UPDATE adverts SET price = ?, quantity = ?, updated_at = ? WHERE id = ?";
            $this->connection->query($advertQuery, $advert->getPrice(), $advert->getQuantity(), $this->date(), $advert->getId());
            $this->connection->query(
                $itemQuery,
                $advert->getItemName(),
                $advert->getItemStateId(),
                $advert->getItemDetails(),
                $advert->getItemLowestCategoryId(),
                $advert->getItemBrand(),
                $advert->getId(),
            );
            return;
        }
        $itemQuery = "INSERT INTO items (id, name, state_id, details, lowest_category_id, brand) values(?, ?, ?, ?, ?, ?)";
        $advertQuery = "INSERT INTO adverts (id, seller_id, price, quantity, created_at) values (?, ?, ?, ?, ?)";

        $this->connection->query(
            $itemQuery,
            $advert->getItemId(),
            $advert->getItemName(),
            $advert->getItemStateId(),
            $advert->getItemDetails(),
            $advert->getItemLowestCategoryId(),
            $advert->getItemBrand(),
        );
        $this->connection->query(
            $advertQuery,
            $advert->getId(),
            $advert->getSellerId(),
            $advert->getPrice(),
            $advert->getQuantity(),
            $this->date(),
        );
    }

    private function saveImagesToDB(array $oldImages, array $newImages, array $shouldExist, string $itemId): void
    {
        $this->connection->query("DELETE FROM item_images WHERE item_id = ? AND id NOT IN ?", $itemId, $shouldExist);
        if ($newImages !== []) {
            $this->connection->query(
                "INSERT INTO item_images ? ON CONFLICT (item_id, id) DO UPDATE SET rank=excluded.rank, extension=excluded.extension, created_at=excluded.created_at",
                $newImages,
            );
        }
        if ($oldImages !== []) {
            $this->connection->query(
                "INSERT INTO item_images ? ON CONFLICT (item_id, id) DO UPDATE SET rank=excluded.rank",
                $oldImages,
            );
        }
    }

    private function saveImagesToFolder(Advert $advert, array $shouldExist): void
    {
        foreach ($advert->getItemImages() as $image) {
            if ($image->getFile() !== null) {
                $image->moveFile(
                    sprintf('%s%s_%s.%s', $this->configuration->get()['itemImagesDirectory'], $item->getId(), $image->getId(), $image->getFileExtension())
                );
            }
        }

        $toDelete = $this->connection->query(
            "SELECT id, extension FROM item_images WHERE item_id = ? AND id NOT IN ?",
            $advert->getId(),
            $shouldExist,
        );
        foreach ($toDelete as $row) {
            unlink(sprintf('%s%s_%s.%s', $this->configuration->get()['itemImagesDirectory'], $item->getId(), $row->id, $row->extension));
        }
    }
}
