<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableItems extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table items
        (name text not null,
        state_id integer constraint fk_state references states,
        details text,
        id varchar not null constraint products_pkey primary key constraint products_id_key unique,
        subsubcategory_id integer not null constraint fk_subsubcategory_id references subsubcategories,
        brand text);");
	}
}