<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableStates extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table states
    (
        name text not null,
    id   serial
        primary key
);");
	}
}