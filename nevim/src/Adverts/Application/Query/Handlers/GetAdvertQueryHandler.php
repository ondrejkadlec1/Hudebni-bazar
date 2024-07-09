<?php

namespace Ondra\App\Adverts\Application\Query\Handlers;

use Ondra\App\Adverts\Application\IAdvertRepository;
use Ondra\App\Adverts\Application\Query\Messages\GetAdvertQuery;
use Ondra\App\Adverts\Application\Query\Messages\GetAdvertResponse;
use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Shared\Application\Query\Query;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetAdvertQueryHandler implements Autowired
{
    private IAdvertRepository $repository;

    /**
     * @param IAdvertRepository $repository
     */
    public function __construct(IAdvertRepository $repository)
    {
        $this->repository = $repository;
    }
    public function __invoke(GetAdvertQuery $query): GetAdvertResponse{
        $dto = $this->repository->getDetail($query->getId());
        return new GetAdvertResponse($dto);
    }
}