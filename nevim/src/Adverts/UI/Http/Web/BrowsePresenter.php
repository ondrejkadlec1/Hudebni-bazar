<?php

namespace Ondra\App\Adverts\UI\Http\Web;

use Nette\Application\UI\Form;
use Ondra\App\Adverts\Application\Query\Messages\GetItemImageQuery;
use Ondra\App\Adverts\UI\Http\Web\forms\AdvertFormFactory;
use Ondra\App\Shared\Application\BusProvider;
use Ondra\App\Shared\UI\Http\Web\FrontendPresenter;

class BrowsePresenter extends FrontendPresenter
{
    private AdvertFormFactory $formFactory;
    protected BusProvider $busProvider;
    public function __construct(AdvertFormFactory $formFactory, BusProvider $busProvider)
    {
        $this->formFactory = $formFactory;
        $this->busProvider = $busProvider;
    }

    public function createComponentAdvertsList(): AdvertsListControl
    {
        return new AdvertsListControl($this->busProvider);
    }

    public function createComponentAdvertForm(): Form
    {
        $form = $this->formFactory->create();
        $form->onSuccess[] = function (){
            $this->flashMessage('Přidáno');
        };
        return $form;
    }

    public function actionImage(string $imageName)
    {
        $response = $this->sendQuery(new GetItemImageQuery($imageName));
        $this->getHttpResponse()->setHeader('Content-Type', $response->getMimeType());
        $this->sendResponse($response->getFileResponse());
    }
}