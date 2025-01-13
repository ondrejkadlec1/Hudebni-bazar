<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableUsers extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table users
(
    username   varchar(32)       not null
        unique,
    email      text              not null
        unique,
    created_at timestamp,
    id         varchar           not null
        primary key,
    password   varchar(64),
    authtoken  varchar default 1 not null
);");
	}
}