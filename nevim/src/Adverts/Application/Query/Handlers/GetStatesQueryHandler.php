<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Handlers;

use Ondra\App\Adverts\Application\Query\Messages\Request\GetStatesQuery;
use Ondra\App\Adverts\Application\Query\Messages\Response\GetStatesResponse;
use Ondra\App\Adverts\Infrastructure\DatabaseAuxiliaryRepository;
use Ondra\App\Shared\Application\Autowired;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetStatesQueryHandler implements Autowired
{
    public function __construct(
   		private readonly DatabaseAuxiliaryRepository $repository,
   	) {
   	}
    public function __invoke(GetStatesQuery $query): GetStatesResponse
   	{
   		return new GetStatesResponse($this->repository->getStates());
   	}
}
