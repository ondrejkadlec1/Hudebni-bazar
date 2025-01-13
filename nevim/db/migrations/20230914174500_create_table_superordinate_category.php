<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableSuperordinateCategory extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table superordinate_category
(
    higher_id integer not null
        constraint fk_higher
            references categories,
    lower_id  integer not null
        unique
        constraint fk_lower
            references categories,
    constraint superordinate_category_check
        check (higher_id <> lower_id)
);"
        );
	}
}