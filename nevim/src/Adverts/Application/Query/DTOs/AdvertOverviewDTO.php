<?php

namespace Ondra\App\Adverts\Application\Query\DTOs;

class AdvertOverviewDTO
{
    private string $id;
    private string $name;
    private int $price;
    private string $state;
    private string $subsubcategory;
    private ?string $brand;
    private string $sellerName;
    private int $sellerId;
    private string $details;
    private string $createdAt;
    private ?string $lastUpdate;
    private ?string $mainImageName;

    /**
     * @param string $id
     * @param string $name
     * @param int $price
     * @param string $state
     * @param string $sellerName
     * @param string $subsubcategory
     * @param string $brand
     * @param int $sellerId
     * @param string $details
     * @param string $createdAt
     * @param string|null $lastUpdate
     * @param string|null $mainImageName
     */
    public function __construct(string $id, string $name, int $price, string $state, string $sellerName, int $sellerId, string $details, string $subsubcategory, string $createdAt, ?string $brand, ?string $lastUpdate, ?string $mainImageName)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->state = $state;
        $this->sellerName = $sellerName;
        $this->sellerId = $sellerId;
        $this->details = $details;
        $this->createdAt = $createdAt;
        $this->lastUpdate = $lastUpdate;
        $this->mainImageName = $mainImageName;
        $this->subsubcategory = $subsubcategory;
        $this->brand = $brand;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    public function getSubsubcategory(): string
    {
        return $this->subsubcategory;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }
    public function getBrand(): ?string
    {
        return $this->brand;
    }
    /**
     * @return string
     */
    public function getSellerName(): string
    {
        return $this->sellerName;
    }

    /**
     * @return int
     */
    public function getSellerId(): int
    {
        return $this->sellerId;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string|null
     */
    public function getLastUpdate(): ?string
    {
        return $this->lastUpdate;
    }

    /**
     * @return string|null
     */
    public function getMainImageName(): ?string
    {
        return $this->mainImageName;
    }

}