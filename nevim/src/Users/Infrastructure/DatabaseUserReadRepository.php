<?php

declare(strict_types=1);

namespace Ondra\App\Users\Infrastructure;

use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Security\SimpleIdentity;
use Ondra\App\Shared\Infrastructure\CET;
use Ondra\App\Users\Application\IUserReadRepository;
use Ondra\App\Users\Application\Query\DTOs\IProfileDTO;
use Ondra\App\Users\Application\Query\DTOs\ProfileDTO;
use Ondra\App\Users\Application\Query\DTOs\SellerProfileDTO;

final class DatabaseUserReadRepository implements IUserReadRepository
{
    use CET;
	public function __construct(private readonly Explorer $explorer)
	{
	}

	public function getAuthtoken(string $id): ?string
	{
		return $this->explorer->fetch('SELECT authtoken FROM users WHERE id = ?', $id) ?->authtoken;
	}
	private function createIdentity(Row $identityData): SimpleIdentity
	{
		if (isset($identityData->seller_id)) {
			$role = 'seller';
		} else {
			$role = 'user';
		}
		return new SimpleIdentity($identityData->id, $role);
	}
	public function getPasswordHash(string $username): ?string
	{
		return $this->explorer->fetch('SELECT password FROM users WHERE username = ?', $username) ?->password;
	}
	public function getIdentityByAuthtoken(string $authtoken): ?SimpleIdentity
	{
		$identityData = $this->explorer->fetch(
			'SELECT users.id AS id, sellers.id AS seller_id FROM users LEFT JOIN sellers ON users.id = sellers.id WHERE authtoken = ?',
			$authtoken,
		);
		return $identityData
			? $this->createIdentity($identityData)
			: null;
	}
	public function getIdentityByUsername(string $username): ?SimpleIdentity
	{
		$identityData = $this->explorer->fetch(
			'SELECT users.id AS id, sellers.id AS seller_id FROM users LEFT JOIN sellers ON users.id = sellers.id WHERE username = ?',
			$username,
		);
        return $identityData
            ? $this->createIdentity($identityData)
            : null;
	}
	public function getSellerProfile(string $id): ?SellerProfileDTO
	{
		$data = $this->explorer->fetch('SELECT username, created_at, sellers.id AS seller_id, description FROM users LEFT JOIN sellers ON users.id = sellers.id WHERE users.id = ?', $id);
		if (isset($data->seller_id)) {
			return new SellerProfileDTO($data->description, new ProfileDTO($data->username, $this->toCET($data->created_at)));
		}
		return null;
	}
    public function getAnyProfile(string $id): ?IProfileDTO
    {
        $data = $this->explorer->fetch('SELECT username, created_at, sellers.id AS seller_id, description FROM users LEFT JOIN sellers ON users.id = sellers.id WHERE users.id = ?', $id);
        if (isset($data->username)) {
            $user = new ProfileDTO($data->username, $this->toCET($data->created_at));
            if (isset($data->seller_id)) {
                return new SellerProfileDTO($data->description, $user);
            }
            return $user;
        }
        return null;
    }
    public function getSellerName(string $id): ?string
    {
        $data = $this->explorer->fetch('SELECT username, sellers.id AS seller_id FROM users LEFT JOIN sellers ON users.id = sellers.id WHERE users.id = ?', $id);
        if (isset($data->seller_id)) {
            return $data->username;
        }
        return null;
    }
}

