<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableCategories extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table categories
        (id serial primary key,
        name varchar not null);");
	}
}