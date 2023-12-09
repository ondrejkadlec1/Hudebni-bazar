<?php

namespace Ondra\App\System\UI\Http\Web;

use Nette\Application\UI\Presenter;
use Nette\Database\Explorer;
use Nette\Database\Connection;
use Nette\Application\UI\Form;
class PostsPresenter extends Presenter
{
    public function __construct(
        private Explorer $explorer,
        private Connection $connection,
    ) {
    }
    protected function createComponentPostForm(): Form
    {
        $form = new Form;
        $form->addText('title', 'Název')
            ->setRequired("Zadé název, ty jitrnico!");
        $form->addText('content', 'Obsah')
            ->setRequired("Seš úplná móka? Mosíš tam aspoň něco nadatlovat!");
        $form->addSubmit('send', 'Zveřejnit');
        $form->onSuccess[] = [$this, 'formSucceeded'];
        return $form;
    }
    public function formSucceeded(Form $form, $data){
        $this->connection->query("INSERT INTO posts(title, content) VALUES(?, ?)", $data->title, $data->content);
        $this->flashMessage('Přidáno');
        $this->redirect('Posts:show');
    }
    public function renderShow(): void
    {
        $this->template->posts = $this->explorer
            ->table('posts')
            ->order('created_at DESC')
            ->limit(36);
    }
}