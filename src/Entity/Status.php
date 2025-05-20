<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    /**
     * @var Collection<int, Interet>
     */
    #[ORM\OneToMany(targetEntity: Interet::class, mappedBy: 'status')]
    private Collection $interets;

    public function __construct()
    {
        $this->interets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Interet>
     */
    public function getInterets(): Collection
    {
        return $this->interets;
    }

    public function addInteret(Interet $interet): static
    {
        if (!$this->interets->contains($interet)) {
            $this->interets->add($interet);
            $interet->setStatus($this);
        }

        return $this;
    }

    public function removeInteret(Interet $interet): static
    {
        if ($this->interets->removeElement($interet)) {
            // set the owning side to null (unless already changed)
            if ($interet->getStatus() === $this) {
                $interet->setStatus(null);
            }
        }

        return $this;
    }
}
