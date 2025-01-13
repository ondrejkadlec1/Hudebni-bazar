<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableAdverts extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table adverts
(
    price      integer   not null,
    quantity   integer   not null,
    created_at timestamp not null,
    updated_at timestamp,
    id         varchar   not null
        primary key
        constraint fk_product
            references items,
    seller_id  varchar
        constraint fk_seller_id
            references users
);");
	}
}