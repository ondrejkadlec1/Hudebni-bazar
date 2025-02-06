<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Handlers;

use Ondra\App\Adverts\Application\IAuxiliaryRepository;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetListNameQuery;
use Ondra\App\Adverts\Application\Query\Messages\Response\GetListNameResponse;
use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetListNameQueryHandler implements Autowired
{
    public function __construct(private readonly IAuxiliaryRepository $repository)
   	{
   	}
    public function __invoke(GetListNameQuery $query): GetListNameResponse
   	{
   		$name = $this->repository->getCategoryName($query->id);
   		if ($name === null) {
   			throw new MissingContentException('category with id ' . $query->id . ' does not exist', 1);
   		}
   		return new GetListNameResponse($name);
   	}
}
