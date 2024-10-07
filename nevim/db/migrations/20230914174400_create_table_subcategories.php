<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableSubcategories extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table subcategories
        (id serial primary key,
        category_id integer not null constraint fk_category_id references categories,
        name varchar not null);");
	}
}