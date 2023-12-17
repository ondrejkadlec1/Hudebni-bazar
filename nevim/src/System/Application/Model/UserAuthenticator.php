<?php
namespace Ondra\App\System\Application\Model;

use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Nette\Security\Authenticator;
use Nette\Database\Explorer;
use Nette\Security\AuthenticationException;

class UserAuthenticator implements Authenticator
{
    public function __construct(
        private Explorer $explorer,
        private Passwords $password
    ){
    }
    public function authenticate(string $user, string $password): SimpleIdentity{
        $row = $this->explorer->table('users')
                                ->select('password AS hash')
                                ->where('username', $user)
                                ->fetch();

        if(!isset($row->hash)){
            throw new AuthenticationException('Takový uživatel tady ani není.');
        }
        if (!$this->password->verify($password, $row->hash)){
            throw new AuthenticationException('Špatné heslo.');
        }
        return new SimpleIdentity(0);
    }
}