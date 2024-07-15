<?php

namespace Ondra\App\Adverts\UI\Http\Web;

use Ondra\App\Adverts\Application\Query\Messages\GetSellerProfileQuery;
use Ondra\App\Shared\UI\Http\Web\FrontendPresenter;

class SellerPresenter extends FrontendPresenter
{
    use Navigation;
    public function renderProfile(int $id){
        $this->template->profile = $this->sendQuery(new GetSellerProfileQuery($id))->getDto();
    }

}