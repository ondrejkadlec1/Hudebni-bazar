<?php

namespace Ondra\App\Adverts\Infrastructure;

use Nette\Database\Explorer;
use Ondra\App\Adverts\Application\ICategoryRepository;

class DatabaseCategoryRepository implements ICategoryRepository
{
    private Explorer $explorer;
    public function __construct(Explorer $explorer)
    {
        $this->explorer = $explorer;
    }
    public function getCategories(): array
    {
        foreach ($this->explorer->table('categories') as $categoryData){
         $categories[$categoryData->id] = $categoryData->name;
        }
        return $categories;
    }

    public function getSubcategories(): array
    {
        foreach ($this->explorer->table('subcategories') as $subcategoryData){
            $subcategories[$subcategoryData->id] = $subcategoryData->name;
        }
        return $subcategories;
    }

    public function getSubsubcategories(): array
    {
        foreach ($this->explorer->table('subsubcategories') as $subsubcategoryData){
            $subsubcategories[$subsubcategoryData->id] = $subsubcategoryData->name;
        }
        return $subsubcategories;
    }

}