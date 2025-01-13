<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Handlers;

use Ondra\App\Adverts\Application\Query\Messages\Request\GetAdvertsCountQuery;
use Ondra\App\Adverts\Application\Query\Messages\Response\GetAdvertsCountResponse;
use Ondra\App\Adverts\Infrastructure\DatabaseAdvertReadRepository;
use Ondra\App\Shared\Application\Autowired;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetAdvertsCountQueryHandler implements Autowired
{
    public function __construct(
   		private readonly DatabaseAdvertReadRepository $repository,
   	) {
   	}
    public function __invoke(GetAdvertsCountQuery $query): GetAdvertsCountResponse
   	{
   		return new GetAdvertsCountResponse($this->repository->getCount($query->criteria));
   	}
}
