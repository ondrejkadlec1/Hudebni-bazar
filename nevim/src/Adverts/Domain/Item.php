<?php

namespace Ondra\App\Offers\Domain;

class Item
{
    private string  $id;
    private string $name;
    private string $details;
    private int $stateId;
    private array $imageNames;

    public function __construct($id, $name, $stateId, $details, $imageNames)
    {
        $this->id = $id;
        $this->name = $name;
        $this->details = $details;
        $this->stateId = $stateId;
        $this->imageNames = $imageNames;
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

    public function setStateId(string $stateId): void
    {
        $this->stateId = $stateId;
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function getImageNames(): array
    {
        return $this->imageNames;
    }
    public function addImageNames(array $imageNames): void
    {
        foreach ($imageNames as $imageName){
            $this->imageNames[] = $imageName;
        }
    }
    public function removeImageNames(array $imagesToRemove): void
    {
        foreach ($this->imageNames as $imageName){
            if(in_array($imageName, $imagesToRemove)){
                unset($imageName);
            }
        }
    }
}
