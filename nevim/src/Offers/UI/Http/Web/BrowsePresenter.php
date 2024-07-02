<?php

namespace Ondra\App\System\UI\Http\Web;

use Ondra\App\System\UI\Http\Web\SolutionsListControl;
use Nette\Application\UI\Presenter;
use Nette\Database\Connection;
use Nette\Database\Explorer;
use Nette\Application\UI\Form;
class SolutionsPresenter extends Presenter
{
    public function __construct(
        private Explorer $explorer,
        private Connection $connection
    ) {
    }

    public function beforeRender()
    {
        $this->session->start();
        $this->template->backlink = $this->storeRequest();
    }
    protected function createComponentSolutionsList(): SolutionsListControl {
        $solutionsList = new SolutionsListControl($this->explorer);
        return $solutionsList;
    }

    protected function createComponentPostForm(): Form
    {
        $form = new Form;
        $form->addText('title', 'Název')
            ->setRequired("Zadé název, ty jitrnico!");
        $form->addText('content', 'Obsah')
            ->setRequired("Seš úplná móka? Mosíš tam aspoň něco nadatlovat!");
        $form->addSubmit('send', 'Zveřejnit');
        $form->onSuccess[] = [$this, 'formSubmitted'];
        return $form;
    }
    public function formSubmitted(Form $form, \stdClass $data){
        $this->connection->query("INSERT INTO posts(title, content) VALUES(?, ?)", $data->title, $data->content);
        $this->flashMessage('Přidáno');
    }
}