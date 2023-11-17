<?php

declare(strict_types=1);

namespace Ondra\App\Test\Application\Query;

use Nette\Database\Connection;
use Ondra\App\System\Application\Autowired;
use Ondra\App\Test\Application\Exception\StatusNotFound;
use Ondra\App\Test\Domain\Status\Status;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class StatusHandler implements Autowired
{
	public function __construct(private readonly Connection $db)
	{
	}

	public function __invoke(StatusQuery $query): StatusQuery
	{
		$row = $this->db->query('SELECT * FROM status WHERE id = ?', $query->id)->fetch();

		if ($row === null) {
			throw new StatusNotFound(sprintf('Status with id %s not found', $query->id));
		}

		$query->setStatus(new Status($row->id, $row->name));

		return $query;
	}
}
