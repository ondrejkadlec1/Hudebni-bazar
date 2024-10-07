<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\Application\Query\Messages\Response;

use Nette\Application\Responses\FileResponse;
use Ondra\App\Shared\Application\Query\Query;

final readonly class GetItemImageResponse implements Query
{
	public function __construct(public string $mimeType, public FileResponse $fileResponse)
	{
	}
}
