<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var Collection<int, RoadTrip>
     */
    #[ORM\OneToMany(targetEntity: RoadTrip::class, mappedBy: 'type')]
    private Collection $roadTrips;

    public function __construct()
    {
        $this->roadTrips = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->updated_at = new \DateTimeImmutable();

        if ($this->created_at === null) {
            $this->created_at = new \DateTimeImmutable();
        }
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, RoadTrip>
     */
    public function getRoadTrips(): Collection
    {
        return $this->roadTrips;
    }

    public function addRoadTrip(RoadTrip $roadTrip): static
    {
        if (!$this->roadTrips->contains($roadTrip)) {
            $this->roadTrips->add($roadTrip);
            $roadTrip->setType($this);
        }

        return $this;
    }

    public function removeRoadTrip(RoadTrip $roadTrip): static
    {
        if ($this->roadTrips->removeElement($roadTrip)) {
            // set the owning side to null (unless already changed)
            if ($roadTrip->getType() === $this) {
                $roadTrip->setType(null);
            }
        }

        return $this;
    }
}
