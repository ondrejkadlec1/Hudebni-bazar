<?php

namespace Ondra\App\Adverts\Application\Command;

class AdvertDTO
{
    private string $name;
    private int $stateId;
    private string $details;
    private int $price;
    private int $quantity;
    private array $images;
    private int $subsubcategoryId;

    /**
     * @param string $name
     * @param int $stateId
     * @param string $details
     * @param int $price
     * @param int $quantity
     * @param array $images
     * @param int $subsubcategoryId
     */
    public function __construct(string $name, int $stateId, string $details, int $price, int $quantity, array $images, int $subsubcategoryId)
    {
        $this->name = $name;
        $this->stateId = $stateId;
        $this->details = $details;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->images = $images;
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
     * @return int
     */
    public function getStateId(): int
    {
        return $this->stateId;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return int
     */
    public function getSubsubcategoryId(): int
    {
        return $this->subsubcategoryId;
    }
}