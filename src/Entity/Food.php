<?php

namespace App\Entity;

use App\Repository\FoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Jaime;
use App\Entity\Commentaire;
use App\Entity\Ingredient;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
class Food
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $libelle;

    #[ORM\Column(type: 'string', length: 1000)]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    private $photo;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'food',cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'food',cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private $category;

    #[ORM\OneToMany(mappedBy: 'food', targetEntity: Jaime::class)]
    private $jaimes;

    #[ORM\OneToMany(mappedBy: 'food', targetEntity: Commentaire::class)]
    private $commentaires;

    #[ORM\OneToMany(mappedBy: 'food', targetEntity: Ingredient::class)]
    private $ingredients;

    #[ORM\Column(type: 'string', length: 255)]
    private $photo_url;


    public function tojson(bool $jaime=false,bool $commentaire=false,bool $ingredient=false): ?array
    {
        return $this ? [
            'id' => $this->id,
            'libelle' => $this->libelle,
            'description' => $this->description,
            'photo' => $this->photo,
            'photo_url'=>$this->photo_url,
            'createdAt' => $this->createdAt,
            'user'=>$this->user?$this->user->tojson():null,
            'category'=>$this->category?$this->category->tojson():null,
            'jaimes'=>$jaime ? array_map(function(Jaime $jaime){
                return $jaime->tojson();
            },$this->jaimes->getValues()):[],
            'commentaires'=>$commentaire ? array_map(function(Commentaire $commentaire){
                return $commentaire->tojson();
            },$this->commentaires->getValues()):[],
            'ingredients'=>$ingredient ? array_map(function(Ingredient $ingredient){
                return $ingredient->tojson();
            },$this->ingredients->getValues()):[]
        ] : null;
    }

    public function __construct()
    {
        $this->createdAt=new \DateTime();
        $this->jaimes = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Jaime>
     */
    public function getJaimes(): Collection
    {
        return $this->jaimes;
    }

    public function addJaime(Jaime $jaime): self
    {
        if (!$this->jaimes->contains($jaime)) {
            $this->jaimes[] = $jaime;
            $jaime->setFood($this);
        }

        return $this;
    }

    public function removeJaime(Jaime $jaime): self
    {
        if ($this->jaimes->removeElement($jaime)) {
            // set the owning side to null (unless already changed)
            if ($jaime->getFood() === $this) {
                $jaime->setFood(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setFood($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getFood() === $this) {
                $commentaire->setFood(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setFood($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getFood() === $this) {
                $ingredient->setFood(null);
            }
        }

        return $this;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photo_url;
    }

    public function setPhotoUrl(string $photo_url): self
    {
        $this->photo_url = $photo_url;

        return $this;
    }
}
