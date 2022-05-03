<?php

namespace App\Entity;

use App\Repository\JaimeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JaimeRepository::class)]
class Jaime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $jaime;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'jaimes',cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Food::class, inversedBy: 'jaimes',cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private $food;

    public function tojson(): ?array
    {
        return $this ? [
            'id' => $this->id,
            'jaime' => $this->jaime,
            'user'=>$this->user?$this->user->tojson():null,
            'food'=>$this->food?$this->food->tojson():null,
        ] : null;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJaime(): ?int
    {
        return $this->jaime;
    }

    public function setJaime(int $jaime): self
    {
        $this->jaime = $jaime;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFood(): ?Food
    {
        return $this->food;
    }

    public function setFood(?Food $food): self
    {
        $this->food = $food;

        return $this;
    }
}
