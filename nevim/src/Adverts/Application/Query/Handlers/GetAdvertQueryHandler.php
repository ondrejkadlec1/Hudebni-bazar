<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Handlers;

use Ondra\App\Adverts\Application\IAdvertReadRepository;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetAdvertQuery;
use Ondra\App\Adverts\Application\Query\Messages\Response\GetAdvertResponse;
use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetAdvertQueryHandler implements Autowired
{
    public function __construct(private readonly IAdvertReadRepository $repository)
   	{
   	}
    public function __invoke(GetAdvertQuery $query): GetAdvertResponse
   	{
   		$dto = $this->repository->getDetail($query->id);
   		if ($dto === null) {
   			throw new MissingContentException('advert does not exist', 0);
   		}
   		return new GetAdvertResponse($dto);
   	}
}
