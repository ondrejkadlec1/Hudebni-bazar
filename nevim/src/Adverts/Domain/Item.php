<?php

namespace Ondra\App\Adverts\Domain;

class Item
{
    private string  $id;
    private string $name;
    private string $details;
    private int $stateId;
    private array $itemImages;
    private int $subsubcategoryId;

    public function __construct(string $id, string $name, int $stateId, string $details, array $itemImages, int $subsubcategoryId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->details = $details;
        $this->stateId = $stateId;
        $this->itemImages = $itemImages;
        $this->subsubcategoryId = $subsubcategoryId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * @param string $details
     */
    public function setDetails(string $details): void
    {
        $this->details = $details;
    }
    public function getStateId(): int
    {
        return $this->stateId;
    }
    public function setSubsubcategoryId(int $subsubcategoryId): void
    {
        $this->subsubcategoryId = $subsubcategoryId;
    }
    public function getSubsubcategoryId(): int
    {
        return $this->subsubcategoryId;
    }
    public function setStateId(string $stateId): void
    {
        $this->stateId = $stateId;
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function getItemImages(): array
    {
        return $this->itemImages;
    }
    public function addItemImages(array $itemImages): void
    {
        foreach ($itemImages as $itemImage){
            $this->itemImages[] = $itemImage;
        }
    }
    public function removeItemImages(array $imagesToRemove): void
    {
        foreach ($this->itemImages as $imageName){
            if(in_array($imageName, $imagesToRemove)){
                unset($imageName);
            }
        }
    }
}
