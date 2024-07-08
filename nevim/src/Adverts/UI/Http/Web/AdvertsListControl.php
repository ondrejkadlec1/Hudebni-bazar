<?php

namespace Ondra\App\Offers\UI\Http\Web;

use Nette\Application\UI\Control;
use Ondra\App\Offers\Application\Query\GetOffersQuery;
use Ondra\App\Shared\Application\BusProvider;

class OffersListControl extends Control
{
    private BusProvider $busProvider;
    /**
     * @param BusProvider $busProvider
     */
    public function __construct(BusProvider $busProvider)
    {
        $this->busProvider = $busProvider;
    }

    public function render(): void
    {
        foreach($this->busProvider->sendQuery(new GetOffersQuery())->offers as $offer){
            $offerInfo = [
            'id' => $offer->getId(),
            'name' => $offer->getProduct()->getName(),
            'price' => $offer->getPrice(),
            'state' => $offer->getProduct()->getState(),
            'createdAt' => (string)$offer->getCreatedAt(),
            'seller' => $offer->getSeller()->getUsername(),
            'details' => $offer->getProduct()->getDetails(),
            ];
            if($offer->getUpdatedAt()!==null){
                $offerInfo['lastUpdate'] = (string)$offer->getUpdatedAt()->__toString();
            }
            if($offer->getProduct()->getImages()!==null){
                $offerInfo['imageLink'] = '/image/' . $offer->getProduct()->getImages()[0] . '/';
            }
            $this->template->offers[] = $offerInfo;
        }
        $this->template->render(__DIR__ . "/templates/Browse/offersList.latte");
    }
}