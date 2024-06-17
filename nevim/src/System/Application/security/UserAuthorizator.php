<?php

namespace Ondra\App\System\UI\Http\Web\forms;

use Nette\Security\Authorizator;

class UserAuthorizator implements Authorizator
{
    public function isAllowed($role, $resource, $privilege): bool {
    return false;
    }
}