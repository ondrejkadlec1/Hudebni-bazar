<?php
namespace Ondra\App\System\UI\Http\Web\forms;

use Nette\Database\Explorer;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;

class UserAuthenticator implements Authenticator
{
    public function __construct(
        private Explorer $explorer,
        private Passwords $password
    ){
    }
    public function authenticate(string $user, string $password): SimpleIdentity {
        $row = $this->explorer->fetch('SELECT * FROM users WHERE username = ?', $user);

            if(!isset($row->password)){
                throw new AuthenticationException('Takový uživatel tady ani není.');
            }
            if (!$this->password->verify($password, $row->password)){
                throw new AuthenticationException('Špatné heslo.');
            }
            return new SimpleIdentity($row->id, null, ['username' => $row->username]);
    }
}