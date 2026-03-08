<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, Listing>
     */
    #[ORM\OneToMany(targetEntity: Listing::class, mappedBy: 'user')]
    private \Doctrine\Common\Collections\Collection $listings;

    public function __construct()
    {
        $this->listings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, Listing>
     */
    public function getListings(): \Doctrine\Common\Collections\Collection
    {
        return $this->listings;
    }

    public function addListing(Listing $listing): static
    {
        if (!$this->listings->contains($listing)) {
            $this->listings->add($listing);
            $listing->setUser($this);
        }

        return $this;
    }

    public function removeListing(Listing $listing): static
    {
        if ($this->listings->removeElement($listing)) {
            // set the owning side to null (unless already changed)
            if ($listing->getUser() === $this) {
                $listing->setUser(null);
            }
        }

        return $this;
    }
}
