<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableSubsubcategories extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table subsubcategories
        (id serial primary key,
        subcategory_id integer not null constraint fk_subcategory_id references subcategories,
        name varchar not null);"
        );
	}
}