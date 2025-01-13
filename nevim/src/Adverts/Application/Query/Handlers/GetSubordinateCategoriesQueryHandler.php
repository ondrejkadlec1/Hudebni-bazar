<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Handlers;

use Ondra\App\Adverts\Application\Query\Messages\Request\GetSubordinateCategoriesQuery;
use Ondra\App\Adverts\Application\Query\Messages\Response\GetSubordinateCategoriesResponse;
use Ondra\App\Adverts\Infrastructure\DatabaseAuxiliaryRepository;
use Ondra\App\Shared\Application\Autowired;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetSubordinateCategoriesQueryHandler implements Autowired
{
    public function __construct(
   		private readonly DatabaseAuxiliaryRepository $repository,
   	) {
   	}
    public function __invoke(GetSubordinateCategoriesQuery $query): GetSubordinateCategoriesResponse
   	{
   		return new GetSubordinateCategoriesResponse($this->repository->getSubordinateCategories($query->superordinate));
   	}
}
