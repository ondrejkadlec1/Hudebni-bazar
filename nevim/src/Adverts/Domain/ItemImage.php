<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Domain;

use Nette\Http\FileUpload;

class ItemImage
{
	private string $id;
	private ?string $extension;
	private string $name;
	public function __construct(FileUpload $file)
	{
		$this->id = uniqid();
		$this->extension = $file->getImageFileExtension();
		$this->name = $this->id . '.' . $this->extension;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getExtension(): string
	{
		return $this->extension;
	}

	public function getName(): string
	{
		return $this->name;
	}
}
