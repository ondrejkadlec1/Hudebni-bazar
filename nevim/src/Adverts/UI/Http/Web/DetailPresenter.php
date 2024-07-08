<?php

namespace Ondra\App\Offers\UI\Http\Web;

use Nette\Application\UI\Presenter;
use Ondra\App\Shared\Application\BusProvider;
use Ondra\App\Shared\UI\Http\Web\FrontendPresenter;
use Ondra\App\Offers\Application\Query\GetOfferQuery;
class DetailPresenter extends FrontendPresenter
{
    public function renderShow(int $id){
        $this->template->offer = $this->sendQuery(new GetOfferQuery($id))->offer;
        $images = $this->template->offer->getProduct()->getImages();
        $this->template->images = [];
        if(isset($images)){
            foreach($images as $image) {
                $imageUrls[] = '/image/' . $image . '/';
            }
            $this->template->mainImage = $imageUrls[0];
            unset($imageUrls[0]);
            $this->template->images = $imageUrls;
        }
    }
}