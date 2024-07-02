<?php

namespace Ondra\App\Offers\UI\Http\Web;

use Nette\Application\UI\Control;
class BrowseListControl extends Control
{
    public  function  __construct(private $explorer)
    {
    }

    public function render(): void {
        $this->template->solutions =
        $this->template->render(__DIR__ . "/templates/Browse/browseList.latte");
    }
}