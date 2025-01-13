<?php

declare(strict_types=1);

namespace Ondra\App\Users\Application;

use Nette\Security\SimpleIdentity;
use Ondra\App\Users\Application\Query\DTOs\IProfileDTO;
use Ondra\App\Users\Application\Query\DTOs\SellerProfileDTO;

interface IUserReadRepository
{
	public function getSellerProfile(string $id): ?SellerProfileDTO;
	public function getAuthtoken(string $id): ?string;
	public function getIdentityByAuthtoken(string $authtoken): ?SimpleIdentity;
	public function getIdentityByUsername(string $username): ?SimpleIdentity;
	public function getPasswordHash(string $username): ?string;
    public function getSellerName(string $id): ?string;
    public function getAnyProfile(string $id): ?IProfileDTO;
}
