<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Domain;

final class Seller
{
	public function __construct(private readonly string $id)
	{
	}

	public function getId(): string
	{
		return $this->id;
	}
}
