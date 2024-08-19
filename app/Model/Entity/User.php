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

    #[ORM\Column(name: 'username', type: 'string', length: 50)]
    public string $username;

    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    public string $name;

    #[ORM\Column(name: 'email', type: 'string', length: 255)]
    public string $email;

    #[ORM\Column(name: 'avatar_url', type: 'string', length: 255)]
    public string $avatarUrl;

    #[ORM\Column(name: 'bio', type: 'text', nullable: true, options: ["collation" => "utf8mb4_unicode_ci"])]
    public ?string $bio = null;

    #[ORM\Column(name: 'profile_url', type: 'string', length: 255)]
    public string $profileUrl;

    #[ORM\Column(name: 'location', type: 'string', length: 50, nullable: true)]
    public ?string $location = null;


    #[ORM\OneToOne(targetEntity: Flower::class, mappedBy: 'user')]
    public ?Flower $flower = null;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    public \DateTime $createdAt;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
}
