<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Domain;

use Nette\Http\FileUpload;

final class ItemImage
{
	public function __construct(private readonly int $id, private readonly ?FileUpload $file = null)
	{
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getFile(): ?FileUpload
	{
		return $this->file;
	}
}
