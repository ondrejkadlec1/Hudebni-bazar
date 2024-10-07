<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableItemImages extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table item_images
        (id varchar not null constraint productimages_pkey primary key,
        item_id varchar not null constraint fk_product_id references items,
        extension  varchar,
        created_at timestamp);");
	}
}