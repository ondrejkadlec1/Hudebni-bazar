<?php

namespace Ondra\App\Adverts\UI\Http\Web;

use Ondra\App\Adverts\Application\Query\Messages\GetAdvertQuery;
use Ondra\App\Adverts\Application\Query\Messages\GetItemImageQuery;
use Ondra\App\Shared\UI\Http\Web\FrontendPresenter;

class DetailPresenter extends FrontendPresenter
{
    public function renderShow(int $id){
        $queryResponse = $this->sendQuery(new GetAdvertQuery($id));
        $this->template->advert = $queryResponse->getDto();
        $this->template->imageNames = $queryResponse->getImageNames();
    }
    public function actionImages(string $imageName)
    {
        $response = $this->sendQuery(new GetItemImageQuery($imageName));
        $this->getHttpResponse()->setHeader('Content-Type', $response->getMimeType());
        $this->sendResponse($response->getFileResponse());
    }
}