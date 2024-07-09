<?php

namespace Ondra\App\Adverts\UI\Http\Web;

use Nette\Application\UI\Control;
use Ondra\App\Adverts\Application\Query\Messages\GetAdvertsQuery;
use Ondra\App\Shared\Application\BusProvider;

class AdvertsListControl extends Control
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
        $this->template->adverts = $this->busProvider->sendQuery(new GetAdvertsQuery(new \stdClass))->getDtos();
        $this->template->render(__DIR__ . "/templates/Browse/advertsList.latte");
    }
}