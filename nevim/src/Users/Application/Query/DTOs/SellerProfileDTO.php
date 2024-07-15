<?php

namespace Ondra\App\Adverts\Application\Query\DTOs;

class SellerProfileDTO
{
    private int $id;
    private string $username;
    private string $createdAt;

    /**
     * @param int $id
     * @param string $username
     * @param string $createdAt
     */
    public function __construct(int $id, string $username, string $createdAt)
    {
        $this->id = $id;
        $this->username = $username;
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}