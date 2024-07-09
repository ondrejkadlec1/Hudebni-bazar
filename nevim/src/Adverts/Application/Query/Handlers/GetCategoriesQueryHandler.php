<?php

namespace Ondra\App\Adverts\Application\Query\Handlers;

use Ondra\App\Adverts\Application\Query\Messages\GetCategoriesQuery;
use Ondra\App\Adverts\Application\Query\Messages\GetCategoriesResponse;
use Ondra\App\Adverts\Infrastructure\DatabaseCategoryRepository;
use Ondra\App\Shared\Application\Autowired;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetCategoriesQueryHandler implements Autowired
{
    private DatabaseCategoryRepository $repository;

    /**
     * @param DatabaseCategoryRepository $repository
     */
    public function __construct(DatabaseCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetCategoriesQuery $query): GetCategoriesResponse
    {
        $categories = $this->repository->getCategories();
        $subcategories = $this->repository->getSubcategories();
        $subsubcategories = $this->repository->getSubsubcategories();
        return new GetCategoriesResponse($categories, $subcategories, $subsubcategories);
    }

}