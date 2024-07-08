<?php

namespace Ondra\App\Adverts\Application\Query;

use Ondra\App\Adverts\Application\IAdvertRepository;
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
    public function __invoke(GetAdvertQuery $query): Query{
        $query->offer = $this->repository->getById($query->id);
        return $query;
    }
}