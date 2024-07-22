<?php

namespace Ondra\App\Adverts\Application\Query\Messages;

use Ondra\App\Shared\Application\Query\Query;

class GetCategoriesResponse implements Query
{
    private array $categories;
    private array $subcategories;
    private array $subsubcategories;

    /**
     * @param array $categories
     * @param array $subcategories
     * @param array $subsubcategories
     */
    public function __construct(array $categories, array $subcategories, array $subsubcategories)
    {
        $this->categories = $categories;
        $this->subcategories = $subcategories;
        $this->subsubcategories = $subsubcategories;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return array
     */
    public function getSubcategories(): array
    {
        return $this->subcategories;
    }

    /**
     * @return array
     */
    public function getSubsubcategories(): array
    {
        return $this->subsubcategories;
    }
}