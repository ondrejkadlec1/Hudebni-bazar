<?php

declare(strict_types=1);

namespace Ondra\App\System\UI\Http\Web;

use Nette\Application\UI\Presenter;
use Nette\Database\Connection;
use Nette\Database\Explorer;

final class HomePresenter extends Presenter
{
    public function __construct(
        private Connection $database,
        private Explorer $explorer,
    ) {
    }
    public function renderDefault(): void
    {
        $this->template->posts = $this->explorer
            ->table('posts')
            ->order('created_at DESC');
    }
}
