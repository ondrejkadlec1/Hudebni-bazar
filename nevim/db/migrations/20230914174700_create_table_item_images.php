<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableItemImages extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table item_images
(
    rank       integer not null,
    item_id    varchar not null
        constraint fk_item_id
            references items,
    extension  varchar,
    created_at timestamp,
    id         integer not null,
    constraint item_images_pk
        primary key (item_id, id)
);
");
	}
}