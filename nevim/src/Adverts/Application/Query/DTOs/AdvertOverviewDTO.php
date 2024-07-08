<?php

namespace Ondra\App\Adverts\Application\Query;

class AdvertOverviewDTO
{
    private string $id;
    private string $name;
    private int $price;
    private string $state;
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
     * @param int $sellerId
     * @param string $details
     * @param string $createdAt
     * @param string|null $lastUpdate
     * @param string|null $mainImageName
     */
    public function __construct(string $id, string $name, int $price, string $state, string $sellerName, int $sellerId, string $details, string $createdAt, ?string $lastUpdate, ?string $mainImageName)
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