<?php

namespace App\Artists\Domain\Entity;

use App\Customers\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\UuidV4;

#[Entity]
class Artist
{
    #[
        Id,
        Column(type: 'uuid', nullable:false),

    ]
    private string $id;
    #[Column]
    private ?string $name = null;
    #[ManyToOne(targetEntity: User::class,inversedBy: 'artists')]
    private ?User $user = null;
    /**
     * @var Collection
     */
    private Collection $songs;

    public function __construct()
    {
        $this->id = (string) (new UuidV4());
        $this->songs = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    /**
     * @param Collection $songs
     */
    public function setSongs(Collection $songs): void
    {
        $this->songs = $songs;
    }
}