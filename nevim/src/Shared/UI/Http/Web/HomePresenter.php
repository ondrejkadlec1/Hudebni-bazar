<?php

declare(strict_types=1);

namespace Ondra\App\System\UI\Http\Web;

use Nette\Application\UI\Presenter;
use Nette\Database\Explorer;
use Nette\Database\Connection;

final class HomePresenter extends Presenter
{
    public function __construct(
        private Explorer $explorer,
        private Connection $connection
    ) {
    }
    public function renderDefault(): void
    {
        $this->template->posts = $this->explorer
            ->table('posts')
            ->order('created_at DESC')
            ->limit(3);
//      $this->connection->query('
//          CREATE TABLE users(
//              id serial PRIMARY KEY,
//              username VARCHAR UNIQUE NOT NULL,
//              password VARCHAR NOT NULL,
//              email VARCHAR UNIQUE NOT NULL,
//              created TIMESTAMP NOT NULL,
//              loggedIn BOOL NOT NULL,
//              )');
//              lastLogin TIMESTAMP

    }
}
