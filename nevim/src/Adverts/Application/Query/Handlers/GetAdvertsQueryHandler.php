<?php

namespace Ondra\App\Adverts\Application\Query;

use Ondra\App\Adverts\Application\IAdvertRepository;
use Ondra\App\Offers\Application\Query\GetAdvertQueryHandler;
use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Shared\Application\Query\Query;
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
        return new GetAdvertsResponse($this->repository->getMultiple($query->getCriteria()));
    }
}