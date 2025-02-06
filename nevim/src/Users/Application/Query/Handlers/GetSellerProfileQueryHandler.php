<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application\Query\Handlers;

use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Users\Application\Query\Messages\GetSellerProfileQuery;
use Ondra\App\Users\Application\Query\Messages\GetSellerProfileResponse;
use Ondra\App\Users\Infrastructure\DatabaseUserReadRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetSellerProfileQueryHandler implements Autowired
{
    public function __construct(
        private readonly DatabaseUserReadRepository $repository,
    )
    {
    }

    public function __invoke(GetSellerProfileQuery $query): GetSellerProfileResponse
    {
        $response = $this->repository->getSellerProfile($query->id);
        if ($response === null) {
            throw new MissingContentException('seller does not exist');
        }
        return new GetSellerProfileResponse($response);
    }
}
