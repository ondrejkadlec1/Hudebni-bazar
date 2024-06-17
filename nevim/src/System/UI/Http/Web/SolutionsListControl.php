<?php

namespace Ondra\App\System\UI\Http\Web\templates;

class SolutionsListControl
{
    public function render($conditionCol, $conditionVal){
        $this->template->solutions = [id => 1, title=>'Řešení', content=>'obsah'];
    }
}