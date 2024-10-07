<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Domain;

class Seller
{
	public function __construct(private string $id)
	{
	}

	public function getId(): string
	{
		return $this->id;
	}
}
