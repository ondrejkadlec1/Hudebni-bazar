<?php

declare(strict_types=1);

namespace Ondra\App\System\Application;

use Ondra\App\System\Application\Command\CommandRequest;
use Ondra\App\System\Application\Query\Query;

interface CQRSCapable
{
	public function sendCommand(CommandRequest $command): void;

	public function sendQuery(Query $query): mixed;

	public function setBusProvider(BusProvider $busProvider): void;
}
