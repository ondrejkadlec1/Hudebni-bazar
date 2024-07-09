<?php

declare(strict_types=1);

namespace Ondra\App\Shared\Application;

use Ondra\App\Shared\Application\Command\CommandRequest;
use Ondra\App\Shared\Application\Query\Query;

trait CQRS
{
	protected BusProvider $busProvider;

	public function sendCommand(CommandRequest $command): void
	{
		$this->busProvider->sendCommand($command);
	}

	/**
	 * @phpstan-template T of Query
	 * @phpstan-param T $query
	 * @phpstan-return T
	 */
	public function sendQuery(Query $query): Query
	{
		return $this->busProvider->sendQuery($query);
	}

	public function setBusProvider(BusProvider $busProvider): void
	{
		$this->busProvider = $busProvider;

		$this->onBusProviderInit();
	}

	protected function onBusProviderInit(): void
	{
	}
}
