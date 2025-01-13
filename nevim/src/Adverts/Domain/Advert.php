<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Domain;

final class Advert
{
	public function __construct(
		private readonly string $id,
		private readonly Item $item,
		private readonly Seller $seller,
		private int $price,
		private int $quantity,
	) {
	}
	public function getId(): string
	{
		return $this->id;
	}
	public function getItem(): Item
	{
		return $this->item;
	}
	public function getSeller(): Seller
	{
		return $this->seller;
	}
	public function getPrice(): int
	{
		return $this->price;
	}
	public function setPrice(int $price): void
	{
		$this->price = $price;
	}
	public function getQuantity(): int
	{
		return $this->quantity;
	}
	public function setQuantity(int $quantity): void
	{
		$this->quantity = $quantity;
	}
}
