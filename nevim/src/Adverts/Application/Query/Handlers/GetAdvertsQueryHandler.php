<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Handlers;

use Ondra\App\Adverts\Application\IAdvertReadRepository;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetAdvertsQuery;
use Ondra\App\Adverts\Application\Query\Messages\Response\GetAdvertsResponse;
use Ondra\App\Shared\Application\Autowired;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetAdvertsQueryHandler implements Autowired
{
    public function __construct(private readonly IAdvertReadRepository $repository)
   	{
   	}
    public function __invoke(GetAdvertsQuery $query): GetAdvertsResponse
   	{
   		return new GetAdvertsResponse($this->repository->getOverviews($query->criteria));
   	}
}
