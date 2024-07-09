<?php

declare(strict_types=1);

namespace Ondra\App\Shared\Application;

use Ondra\App\Shared\Application\Command\CommandRequest;
use Ondra\App\Shared\Application\Query\Query;

interface CQRSCapable
{
	public function sendCommand(CommandRequest $command): void;

	public function sendQuery(Query $query): mixed;

	public function setBusProvider(BusProvider $busProvider): void;
}
