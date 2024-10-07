<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Handlers;

use Ondra\App\Adverts\Application\ICategoryRepository;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetListNameQuery;
use Ondra\App\Adverts\Application\Query\Messages\Response\GetListNameResponse;
use Ondra\App\Shared\Application\Autowired;
use Ondra\App\Shared\Application\Exceptions\InvalidValueException;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetListNameQueryHandler implements Autowired
{
	public function __construct(private readonly ICategoryRepository $repository)
	{
	}

	public function __invoke(GetListNameQuery $query): GetListNameResponse
	{
		switch ($query->type) {
			case "category":
				$name = $this->repository->getCategoryName($query->id);
				break;
			case "subcategory":
				$name = $this->repository->getSubcategoryName($query->id);
				break;
			case "subsubcategory":
				$name = $this->repository->getSubsubcategoryName($query->id);
				break;
			default:
				throw new InvalidValueException(
					'Invalid value in type: ' . $query->type . ". Use predefined constants only.",
					0,
				);
		}
		if (! $name) {
			throw new MissingContentException('category does not exist', 1);
		}
		return new GetListNameResponse($name);
	}
}
