<?php

namespace Ondra\App\adverts\Infrastructure;

use Nette\Database\Connection;
use Nette\Database\Explorer;
use Ondra\App\adverts\Application\IAdvertRepository;
use Ondra\App\adverts\Domain\Advert;
use Ondra\App\adverts\Domain\Item;
use Ondra\App\adverts\Domain\Seller;

final class DatabaseAdvertRepository implements IAdvertRepository
{
    private Explorer $explorer;
    private Connection $connection;
    public function __construct(Explorer $explorer, Connection $connection)
    {
        $this->explorer = $explorer;
        $this->connection = $connection;
    }

    public function getById(int $id): Advert
    {
        $advertData = $this->explorer->fetch("SELECT * from adverts WHERE id = ?", $id);
        $itemData = $this->explorer->fetch("SELECT * from items WHERE id = ?", $advertData->itemid);
        $imagesData = $this->explorer->table('itemimages')->where('item_id = ?', $advertData->itemid);
        $sellerData = $this->explorer->fetch("SELECT id, username from users WHERE id = ?", $advertData->sellerid);

        $item = new Item(
            $itemData->id,
            $itemData->name,
            $itemData->stateid,
            $itemData->details,
            );

        if ($imagesData!==[]){
            $images = [];
            foreach ($imagesData as $data){
                $images[] = $data->id . '.' . $data->extension;
            }
            $item->setImages($images);
        }

        $item->setState($this->explorer->fetch("SELECT name from states WHERE id = ?", $itemData->stateid)->name);

        $seller = new Seller(
            $sellerData->id,
            $sellerData->username
            );

        return new Advert(
            $item,
            $seller,
            $advertData->price,
            $advertData->quantity,
            $advertData->created_at,
            $advertData->updated_at,
            $advertData->id
            );
    }
    public function getAll(): array
    {
        $advertDataArray = $this->explorer->table('adverts')->limit(100)->order('created_at DESC');

        foreach($advertDataArray as $advertData){

            $itemData = $this->explorer->fetch("SELECT * from items WHERE id = ?", $advertData->itemid);
            $imagesData = $this->explorer->fetch("SELECT id, extension from itemimages WHERE item_id = ?", $advertData->itemid);
            $sellerData = $this->explorer->fetch("SELECT id, username from users WHERE id = ?", $advertData->sellerid);


            $item = new Item(
                $itemData->id,
                $itemData->name,
                $itemData->stateid,
                $itemData->details,
            );
            if (isset($imagesData)){
                $images[0] = $imagesData->id . '.' . $imagesData->extension;
                $item->setImages($images);
            }

            $item->setState($this->explorer->fetch("SELECT name from states WHERE id = ?", $itemData->stateid)->name);

            $seller = new Seller(
                $sellerData->id,
                $sellerData->username
            );

            $adverts[] = new Advert(
                $item,
                $seller,
                $advertData->price,
                $advertData->quantity,
                $advertData->created_at,
                $advertData->updated_at,
                $advertData->id
            );
        }
        return $adverts;
    }

    public function save(Advert $advert): void
    {
        $item = $advert->getItem();
        $seller = $advert->getSeller();
        $date = date(DATE_ATOM);

        if($advert->getId()==null){

            $itemQuery = "INSERT INTO items (id, name, stateId, details) values(?, ?, ?, ?)";
            $itemImagesQuery = "INSERT INTO item_images (id, item_id, extension) values (?, ?, ?)";
            $advertQuery = "INSERT INTO adverts (itemId, sellerId, price, quantity, created_at) values (?, ?, ?, ?, ?)";
            $this->connection->query($itemQuery, $item->getId(), $item->getName(), $item->getStateId(), $item->getDetails());

            foreach ($item->getImageNames() as $image){
                $imageId = uniqid();
                $ext = $image->getImageFileExtension();
                $image->move('../src/adverts/Infrastructure/uploads/'. $imageId . '.' . $ext);
                $this->connection->query($itemImagesQuery, $imageId, $item->getId(), $ext);
            }

            $this->connection->query($advertQuery, $item->getId(), $seller->getId(), $advert->getPrice(), $advert->getQuantity(), $date);
        }

        else{

            $itemQuery = "UPDATE items set name = ?, stateId = ?, details = ? WHERE id = ?";
            $advertQuery = "UPDATE adverts set price = ?, quantity = ?, updated_at = ? WHERE id = ?";
            $this->connection->query($itemQuery, $item->getName(), $item->getStateId(), $item->getDetails(), $item->getId());
            $this->connection->query(($advertQuery), $advert->getPrice(), $advert->getQuantity(), $date, $advert->getId());
        }
    }

}