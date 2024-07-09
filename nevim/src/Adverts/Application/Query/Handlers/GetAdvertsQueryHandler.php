<?php

namespace Ondra\App\Adverts\Application\Query\Handlers;

use Ondra\App\Adverts\Application\IAdvertRepository;
use Ondra\App\Adverts\Application\Query\Messages\GetAdvertsQuery;
use Ondra\App\Adverts\Application\Query\Messages\GetAdvertsResponse;
use Ondra\App\Offers\Application\Query\GetAdvertQueryHandler;
use Ondra\App\Shared\Application\Autowired;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetAdvertsQueryHandler implements Autowired
{
    private IAdvertRepository $repository;

    /**
     * @param IAdvertRepository $repository
     */
    public function __construct(IAdvertRepository $repository)
    {
        $this->repository = $repository;
    }
    public function __invoke(GetAdvertsQuery $query): GetAdvertsResponse{
        return new GetAdvertsResponse($this->repository->getOverviews($query->getCriteria()));
    }
}