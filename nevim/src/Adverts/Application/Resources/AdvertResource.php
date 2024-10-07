<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Resources;

use Nette\Security\Resource;

final readonly class AdvertResource implements Resource
{
	public function __construct(private string $sellerId)
	{
	}

	public function getSellerId(): string
	{
		return $this->sellerId;
	}

	function getResourceId(): string
	{
		return 'advert';
	}
}
