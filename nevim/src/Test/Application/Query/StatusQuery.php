<?php

declare(strict_types=1);

namespace Ondra\App\Test\Application\Query;

use Ondra\App\System\Application\Query\Query;
use Ondra\App\Test\Domain\Status\Status;

final class StatusQuery implements Query
{
	private Status $status;

	public function __construct(public readonly int $id)
	{
	}

	public function status(): Status
	{
		return $this->status;
	}

	public function setStatus(Status $status): void
	{
		$this->status = $status;
	}
}
