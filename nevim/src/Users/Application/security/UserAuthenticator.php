<?php
namespace Ondra\App\Users\Application\security;

use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Ondra\App\Users\Infrastructure\IUserRepository;

class UserAuthenticator implements Authenticator
{
    public function __construct(
        private IUserRepository $repository,
        private Passwords       $passwords
    ){
    }
    public function authenticate(string $username, string $password): SimpleIdentity {
        $row = $this->repository->getUserByUsername($username);

        if(!isset($row->password)){
            throw new AuthenticationException('Takový uživatel tady ani není.');
        }
        if (!$this->passwords->verify($password, $row->password)){
            throw new AuthenticationException('Špatné heslo.');
        }
        return new SimpleIdentity($row->id, null, ['username' => $row->username]);
    }
    public function reauthenticate(string $id, string $oldPassword): void {
        $row = $this->repository->getUserById($id);

        if (!$this->passwords->verify($oldPassword, $row->password)) {
            throw new AuthenticationException("Špatné heslo");
        }
    }
}