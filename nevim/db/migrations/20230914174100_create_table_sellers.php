<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableSellers extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table sellers
(
    description text,
    id          varchar
        constraint fk_id
            references users
);");
	}
}