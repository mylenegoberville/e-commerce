<?php

namespace App\Entity;

use App\Repository\BasketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BasketRepository::class)
 */
class Basket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="baskets")
     */
    private $user;

    /**
     * @ORM\Column(type="date")
     */
    private $purchaseDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status = false;

    /**
     * @ORM\OneToMany(targetEntity=BasketContent::class, mappedBy="basket")
     */
    private $basketContents;

    public function __construct()
    {
        $this->purchaseDate = new \DateTime();
        $this->basketContents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(\DateTimeInterface $purchaseDate ): self
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|BasketContent[]
     */
    public function getBasketContents(): Collection
    {
        return $this->basketContents;
    }

    public function addBasketContent(BasketContent $basketContent): self
    {
        if (!$this->basketContents->contains($basketContent)) {
            $this->basketContents[] = $basketContent;
            $basketContent->setBasket($this);
        }

        return $this;
    }

    public function removeBasketContent(BasketContent $basketContent): self
    {
        if ($this->basketContents->removeElement($basketContent)) {
            // set the owning side to null (unless already changed)
            if ($basketContent->getBasket() === $this) {
                $basketContent->setBasket(null);
            }
        }

        return $this;
    }
}
