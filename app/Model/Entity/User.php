<?php

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Id]
    #[ORM\Column(name: 'user_id', type: 'uuid_binary', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    public string $userId;

    #[ORM\Column(name: 'github_id', type: 'string', length: 255, unique: true)]
    public string $githubId;

    #[ORM\Column(name: 'username', type: 'string', length: 255)]
    public string $username;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    public \DateTime $createdAt;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
}
