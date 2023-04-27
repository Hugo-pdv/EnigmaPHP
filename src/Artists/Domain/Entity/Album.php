<?php

namespace App\Artists\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;

#[ORM\Entity]
class Album
{
    #[
        Id,
        Column(type: 'uuid', nullable:false),

    ]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'artists')]
    private Artist $artist;

    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'albums')]
    private Collection $songs;

    public function __construct()
    {
        $this->songs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getArtist(): Artist
    {
        return $this->artist;
    }

    public function setArtist(Artist $artist): Album
    {
        $this->artist = $artist;

        return $this;
    }

    public function setName(?string $name): Album
    {
        $this->name = $name;

        return $this;
    }

    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): Album
    {
        $this->songs->add($song);

        return $this;
    }

    public function removeSong(Song $song): Album
    {
        $this->songs->removeElement($song);

        return $this;
    }

}