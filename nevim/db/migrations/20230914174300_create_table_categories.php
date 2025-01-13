<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableCategories extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table categories
(
    name varchar not null,
    id   serial
        constraint categories_pk
            primary key
        unique
);");
	}
}