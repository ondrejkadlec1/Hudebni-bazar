<?php

declare(strict_types=1);

namespace Ondra\App\Shared\Infrastructure;

use DateTime;
use DateTimeZone;
trait CET
{
	private function toCET(DateTime $date): string
	{
		$date->setTimezone(new DateTimeZone('Europe/Prague'));
		return $date->format('j. n. Y');
	}
}
