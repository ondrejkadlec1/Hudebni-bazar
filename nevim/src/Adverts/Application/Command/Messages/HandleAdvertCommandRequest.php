<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Command\Messages;

use Ondra\App\Adverts\Application\Command\DTOs\AdvertDTO;
use Ondra\App\Shared\Application\Command\CommandRequest;

final readonly class HandleAdvertCommandRequest implements CommandRequest
{
    public function __construct(public AdvertDTO $dto)
    {
    }
}
