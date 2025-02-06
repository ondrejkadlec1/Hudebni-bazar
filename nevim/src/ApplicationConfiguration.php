<?php

namespace Ondra\App;

use Nette\Neon\Neon;

class ApplicationConfiguration
{
    private array $params;
    public function __construct(){
        $appDir = dirname(__DIR__);
        $this->params = Neon::decodeFile($appDir . '/config/application.neon');
    }

    public function get(): array
    {
        return $this->params;
    }

}