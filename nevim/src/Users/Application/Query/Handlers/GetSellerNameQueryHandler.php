<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\Query\Handlers;

use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Users\Application\Query\Messages\GetSellerNameQuery;
use Ondra\App\Users\Application\Query\Messages\GetSellerNameResponse;
use Ondra\App\Users\Infrastructure\DatabaseUserReadRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetSellerNameQueryHandler implements Autowired
{
    public function __construct(
        private readonly DatabaseUserReadRepository $repository,
    )
    {
    }

    public function __invoke(GetSellerNameQuery $query): GetSellerNameResponse
    {
        $name = $this->repository->getSellerName($query->id);
        if ($name === null) {
            throw new MissingContentException('seller does not exist');
        }
        return new GetSellerNameResponse($name);
    }
}
