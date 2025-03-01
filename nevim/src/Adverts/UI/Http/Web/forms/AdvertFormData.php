<?php

namespace Ondra\App\Adverts\UI\Http\Web\forms;

class AdvertFormData
{
    public string $name;
    public int $stateId;
    public int $price;
    public string $details;
    public int $quantity;
    public int $categoryId;
    public ?int $subcategoryId;
    public ?int $subsubcategoryId;
    public string $brand;
    public string $keepImages;
    public array $images;
}