<?php

namespace Ondra\App\System\Application\security;

use Nette\Security\Authorizator;

class UserAuthorizator implements Authorizator
{
    public function isAllowed($role, $resource, $privilege): bool {
    return false;
    }
}