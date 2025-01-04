<?php

declare(strict_types=1);

namespace Ondra\App\Users\Infrastructure;

use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Security\SimpleIdentity;
use Ondra\App\Users\Application\IUserReadRepository;
use Ondra\App\Users\Application\Query\DTOs\SellerProfileDTO;

final class DatabaseUserReadRepository implements IUserReadRepository
{
	public function __construct(private readonly Explorer $explorer)
	{
	}

	public function getAuthtoken(string $id): ?string
	{
		return $this->explorer->fetch('SELECT authtoken FROM users WHERE id = ?', $id) ?->authtoken;
	}
	private function createIdentity(Row $identityData): SimpleIdentity
	{
		if ($identityData->is_seller) {
			$role = 'seller';
		} else {
			$role = 'user';
		}
		return new SimpleIdentity($identityData->id, $role, [
			"username" => $identityData->username,
			"email" => $identityData->email,
		]);
	}
	public function getPasswordHash(string $username): ?string
	{
		return $this->explorer->fetch('SELECT password FROM users WHERE username = ?', $username) ?->password;
	}
	public function getIdentityByAuthtoken(string $authtoken): ?SimpleIdentity
	{
		$identityData = $this->explorer->fetch(
			'SELECT id, username, email, is_seller FROM users WHERE authtoken = ?',
			$authtoken,
		);
		return $identityData
			? $this->createIdentity($identityData)
			: null;
	}
	public function getIdentityByUsername(string $username): ?SimpleIdentity
	{
		$identityData = $this->explorer->fetch(
			'SELECT id, username, email, is_seller FROM users WHERE username = ?',
			$username,
		);
        return $identityData
            ? $this->createIdentity($identityData)
            : null;
	}
	public function getSellerProfile(string $id): ?SellerProfileDTO
	{
		$data = $this->explorer->fetch('SELECT username, is_seller, users_info.* FROM users LEFT JOIN users_info ON users.id = users_info.id WHERE users.id = ?', $id);
		if ($data->is_seller) {
			return new SellerProfileDTO($data->username, $data->description);
		}
		return null;
	}
    public function getSellerName(string $id): ?string
    {
        $data = $this->explorer->fetch('SELECT username, is_seller FROM users WHERE users.id = ?', $id);
        if ($data->is_seller) {
            return $data->username;
        }
        return null;
    }
}

