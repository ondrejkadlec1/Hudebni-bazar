<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Domain;

use Nette\Http\FileUpload;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;

final class ItemImage
{
    public function __construct(private readonly int $id, private readonly ?FileUpload $file = null)
    {
    }

    public function getFile(): ?FileUpload
    {
        return $this->file;
    }

    public function getFileExtension(): ?string
    {
        return $this->file?->getImageFileExtension();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function moveFile(string $path): void
    {
        if ($this->file === null) {
            throw new MissingContentException();
        }
        $this->file->move($path);
    }
}
