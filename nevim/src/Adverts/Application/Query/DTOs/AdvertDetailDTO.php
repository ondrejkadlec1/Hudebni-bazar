<?php

namespace Ondra\App\Adverts\Application\Query\DTOs;

class AdvertDetailDTO
{
    private string $id;
    private string $name;
    private int $price;
    private string $state;
    private string $sellerName;
    private int $sellerId;
    private string $details;
    private string $createdAt;
    private array $imageNames;
    private int $categoryId;
    private int $subcategoryId;
    private int $subsubcategoryId;
    private string $categoryName;
    private string $subcategoryName;
    private string $subsubcategoryName;
    private ?string $brand;
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
     * @param array $imageNames
     * @param int $categoryId
     * @param int $subcategoryId
     * @param int $subsubcategoryId
     * @param string $categoryName
     * @param string $subcategoryName
     * @param string $subsubcategoryName
     * @param string|null $brand
     * @param string|null $lastUpdate
     * @param string|null $mainImageName
     */
    public function __construct(string $id, string $name, int $price, string $state, string $sellerName, int $sellerId, string $details, string $createdAt, array $imageNames, int $categoryId, int $subcategoryId, int $subsubcategoryId, string $categoryName, string $subcategoryName, string $subsubcategoryName, ?string $brand, ?string $lastUpdate, ?string $mainImageName)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->state = $state;
        $this->sellerName = $sellerName;
        $this->sellerId = $sellerId;
        $this->details = $details;
        $this->createdAt = $createdAt;
        $this->imageNames = $imageNames;
        $this->categoryId = $categoryId;
        $this->subcategoryId = $subcategoryId;
        $this->subsubcategoryId = $subsubcategoryId;
        $this->categoryName = $categoryName;
        $this->subcategoryName = $subcategoryName;
        $this->subsubcategoryName = $subsubcategoryName;
        $this->brand = $brand;
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
     * @return array
     */
    public function getImageNames(): array
    {
        return $this->imageNames;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @return int
     */
    public function getSubcategoryId(): int
    {
        return $this->subcategoryId;
    }

    /**
     * @return int
     */
    public function getSubsubcategoryId(): int
    {
        return $this->subsubcategoryId;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    /**
     * @return string
     */
    public function getSubcategoryName(): string
    {
        return $this->subcategoryName;
    }

    /**
     * @return string
     */
    public function getSubsubcategoryName(): string
    {
        return $this->subsubcategoryName;
    }

    /**
     * @return string|null
     */
    public function getBrand(): ?string
    {
        return $this->brand;
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
