<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableUsersInfo extends AbstractMigration
{
	public function change(): void
	{
        $this->execute("create table users_info
        (description text,
        id varchar constraint fk_id references users);");
	}
}