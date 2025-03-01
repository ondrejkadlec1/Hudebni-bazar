<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Command\DTOs;

use Nette\Http\FileUpload;
use Ondra\App\Shared\Application\Exceptions\InvalidValueException;

final readonly class AdvertDTO
{
	public function __construct(
        public string $name,
        public int $stateId,
        public int $price,
        public int $lowestCategoryId,
        public int $quantity,
        public array $images,
        public string $details,
        public ?string $id = null,
        public ?string $brand = null,
	) {
        $this->validate();
	}
    public function validate(): void
    {
        foreach ($this->images as $element) {
            if (getType($element) !== 'int' and !($element instanceof FileUpload)) {
                throw new InvalidValueException('Array "images" should only contain integers.');
            }
        }
    }
}
