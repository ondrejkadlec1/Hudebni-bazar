<?php

namespace Ondra\App\Offers\UI\Http\Web;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use Ondra\App\Offers\UI\Http\Web\forms\OfferFormFactory;
use Ondra\App\Shared\Application\BusProvider;
use Nette\Application\Responses\FileResponse;
use Nette\Utils\Image;

class BrowsePresenter extends Presenter
{
    private OfferFormFactory $formFactory;
    private BusProvider $busProvider;
    public function __construct(OfferFormFactory $formFactory, BusProvider $busProvider)
    {
        $this->formFactory = $formFactory;
        $this->busProvider = $busProvider;
    }

    public function createComponentOffersList(): OffersListControl
    {
        return new OffersListControl($this->busProvider);
    }

    public function createComponentOfferForm(): Form
    {
        $form = $this->formFactory->create();
        $form->onSuccess[] = function (){
            $this->flashMessage('Přidáno');
        };
        return $form;
    }

    public function actionImage(string $imageName)
    {
        $imagePath = "../src/Offers/Infrastructure/uploads/" . $imageName;
        $mimeType = Image::typeToMimeType(Image::detectTypeFromFile($imagePath));
        $this->getHttpResponse()->setHeader('Content-Type', $mimeType);
        $this->sendResponse(new FileResponse($imagePath));
    }
}