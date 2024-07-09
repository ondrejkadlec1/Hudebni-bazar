<?php

namespace Ondra\App\Adverts\Infrastructure;

use Nette\Database\Connection;
use Nette\Database\Explorer;
use Ondra\App\Adverts\Application\IAdvertRepository;
use Ondra\App\Adverts\Application\Query\DTOs\AdvertDetailDTO;
use Ondra\App\Adverts\Application\Query\DTOs\AdvertOverviewDTO;
use Ondra\App\Adverts\Domain\Advert;
use Ondra\App\Adverts\Domain\Item;
use Ondra\App\Adverts\Domain\Seller;

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
        $imagesData = $this->explorer->table('item_images')->where('item_id = ?', $advertData->itemid);
        $sellerData = $this->explorer->fetch("SELECT id, username from users WHERE id = ?", $advertData->sellerid);

        $item = new Item(
            $itemData->id,
            $itemData->name,
            $itemData->state_id,
            $itemData->details,
            [],
            $itemData->subsubcategoryId
            );

        if (!empty($imagesData)){
            $images = [];
            foreach ($imagesData as $data){
                $images[] = $data->id . '.' . $data->extension;
            }
            $item->setImages($images);
        }

        $item->setState($this->explorer->fetch("SELECT name from states WHERE id = ?", $itemData->state_id)->name);

        $seller = new Seller(
            $sellerData->id,
            );

        return new Advert(
            $advertData->id,
            $item,
            $seller,
            $advertData->price,
            $advertData->quantity
            );
    }
    public function getDetail(int $id): AdvertDetailDTO
    {
        $advertData = $this->explorer->fetch("SELECT * from adverts WHERE id = ?", $id);
        $itemData = $this->explorer->fetch("SELECT * from items WHERE id = ?", $advertData->item_id);

        $subsubcategoryData = $this->explorer->fetch("SELECT name, subcategory_id FROM subsubcategories WHERE id = ?", $itemData->subsubcategory_id);
        $subcategoryData = $this->explorer->fetch("SELECT name, category_id FROM subcategories WHERE id = ?", $subsubcategoryData->subcategory_id);
        $categoryName = $this->explorer->fetch("SELECT name FROM subsubcategories WHERE id = ?", $itemData->subsubcategory_id)->name;

        $imagesData = $this->explorer->table('item_images')->select('id, extension')->where('item_id = ?', $advertData->item_id)->order('created_at ASC');
        $sellerUsername = $this->explorer->fetch("SELECT username from users WHERE id = ?", $advertData->seller_id)->username;
        $state = $this->explorer->fetch("SELECT name from states WHERE id = ?", $itemData->state_id)->name;

        $imageNames = [];
        foreach ($imagesData as $image){
            $imageNames[] = $image->id . '.' . $image->extension;
        }

        $mainImageName = null;
        if (isset($imageNames[0])){
            $mainImageName = $imageNames[0];
            unset($imageNames[0]);

        }
        return new AdvertDetailDTO(
            $advertData->id,
            $itemData->name,
            $advertData->price,
            $state,
            $sellerUsername,
            $advertData->seller_id,
            $itemData->details,
            $advertData->created_at,
            $imageNames,
            $subcategoryData->category_id,
            $subsubcategoryData->subcategory_id,
            $itemData->subsubcategory_id,
            $categoryName,
            $subcategoryData->name,
            $subsubcategoryData->name,
            $itemData->brand,
            $advertData->updated_at,
            $mainImageName
        );

    }

    public function getOverviews($criteria): array
    {
        //kritéria
        $advertDataArray = $this->explorer->table('adverts')->limit(100)->order('created_at DESC');
        $dtos = [];
        foreach($advertDataArray as $advertData){

            $itemData = $this->explorer->fetch("SELECT * from items WHERE id = ?", $advertData->item_id);
            $imageData = $this->explorer->fetch("SELECT id, extension from item_images WHERE item_id = ? ORDER BY created_at ASC", $advertData->item_id);
            if (!empty($imageData)){
                $imageName = $imageData->id . '.' . $imageData->extension;
            }
            else{
                $imageName = null;
            }
            $sellerUsername = $this->explorer->fetch("SELECT username from users WHERE id = ?", $advertData->seller_id)->username;

            $subsubcategory = $this->explorer->fetch("SELECT name FROM subsubcategories WHERE id = ?", $itemData->subsubcategory_id)->name;
            $state = $this->explorer->fetch("SELECT name from states WHERE id = ?", $itemData->state_id)->name;

            $dtos[] = new AdvertOverviewDTO(
                $advertData->id,
                $itemData->name,
                $advertData->price,
                $state,
                $sellerUsername,
                $advertData->seller_id,
                $itemData->details,
                $subsubcategory,
                $advertData->created_at,
                $itemData->brand,
                $advertData->updated_at,
                $imageName
            );

        }
        return $dtos;
    }

    public function save(Advert $advert): void
    {
        $item = $advert->getItem();
        $seller = $advert->getSeller();
        $date = function(){return date(DATE_ATOM);};

        $itemQuery = "INSERT INTO items (id, name, state_id, details, subsubcategory_id) values(?, ?, ?, ?, ?)";
        $itemImagesQuery = "INSERT INTO item_images (id, item_id, extension, created_at) values (?, ?, ?, ?)";
        $advertQuery = "INSERT INTO adverts (item_id, seller_id, price, quantity, created_at) values (?, ?, ?, ?, ?)";

        $this->connection->query($itemQuery, $item->getId(), $item->getName(), $item->getStateId(), $item->getDetails(), $item->getSubsubcategoryId());
        $this->connection->query($advertQuery, $item->getId(), $seller->getId(), $advert->getPrice(), $advert->getQuantity(), $date());

        foreach ($item->getItemImages() as $image){
            $this->connection->query($itemImagesQuery, $image->getId(), $item->getId(), $image->getExtension(), $date());
        }
    }
}