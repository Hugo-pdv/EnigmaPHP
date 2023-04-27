<?php

namespace App\Artists\Domain\Entity;

use App\Artists\Domain\Repository\ArtistRepository;
use App\Customers\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity]
class Artist
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private string $id;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'artists')]
    private ?User $user;

    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'artists')]
    private ?Collection $songs;

    #[ORM\OneToMany(mappedBy: 'artist', targetEntity: Album::class)]
    private ?Collection $albums;

    public function __construct()
    {
        $this->id = (string) (new UuidV4());
        $this->songs = new ArrayCollection();
        $this->albums = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Artist
    {
        $this->user = $user;

        return $this;
    }

    public function setName(?string $name): Artist
    {
        $this->name = $name;

        return $this;
    }

    public function getSongs(): ?Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): Artist
    {
        $this->songs->add($song);

        return $this;
    }

    public function removeSong(Song $song): Artist
    {
        $this->songs->removeElement($song);

        return $this;
    }

    public function getAlbums(): ?Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): Artist
    {
        $this->albums->add($album);

        return $this;
    }

    public function removeAlbum(Album $album): Artist
    {
        $this->albums->removeElement($album);

        return $this;
    }
}