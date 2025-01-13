<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Handlers;

use Ondra\App\Adverts\Application\Query\Messages\Request\GetCategoriesQuery;
use Ondra\App\Adverts\Application\Query\Messages\Response\GetCategoriesResponse;
use Ondra\App\Adverts\Infrastructure\DatabaseAuxiliaryRepository;
use Ondra\App\Shared\Application\Autowired;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetCategoriesQueryHandler implements Autowired
{
    public function __construct(
   		private readonly DatabaseAuxiliaryRepository $repository,
   	) {
   	}
    public function __invoke(GetCategoriesQuery $query): GetCategoriesResponse
   	{
   		$categories = $this->repository->getCategories();
   		$subcategories = $this->repository->getSubcategories();
   		$subsubcategories = $this->repository->getSubsubcategories();
   		return new GetCategoriesResponse($categories, $subcategories, $subsubcategories);
   	}
}
