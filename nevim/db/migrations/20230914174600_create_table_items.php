<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableItems extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table items
(
    name               text    not null,
    state_id           integer
        constraint fk_state
            references states,
    details            text,
    id                 varchar not null
        constraint products_pkey
            primary key,
    lowest_category_id integer not null
        constraint lowest_category_fk
            references categories,
    brand              text
);");
	}
}