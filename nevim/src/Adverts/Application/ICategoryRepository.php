<?php

namespace Ondra\App\Adverts\Application;

interface ICategoryRepository
{
    public function getCategories(): array;
    public function getSubcategories(): array;
    public function getSubsubcategories(): array;
}