<?php

namespace App\Entity;

use App\Repository\BucketlistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Blameable;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: BucketlistRepository::class)]
class Bucketlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $display = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Timestampable(on: 'create')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Timestampable(on: 'update')]
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Blameable(on: 'create')]
    #[ORM\ManyToOne(inversedBy: 'bucketlists')]
    private ?User $user = null;

    #[ORM\OneToMany(
        mappedBy: 'bucketList',
        targetEntity: BucketlistItem::class,
        cascade: ['persist'],
        orphanRemoval: true)
    ]
    private Collection $bucketlistItems;

    #[ORM\OneToOne(mappedBy: 'Bucketlist', cascade: ['persist', 'remove'])]
    private ?ShareBucketlist $shareBucketlist = null;

    public function __construct()
    {
        $this->bucketlistItems = new ArrayCollection();
    }

    public function __toString(): string {
        return $this->display;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function setDisplay(string $display): self
    {
        $this->display = $display;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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

    /**
     * @return Collection<int, BucketlistItem>
     */
    public function getBucketlistItems(): Collection
    {
        return $this->bucketlistItems;
    }

    public function addBucketlistItem(BucketlistItem $bucketlistItem): self
    {
        if (!$this->bucketlistItems->contains($bucketlistItem)) {
            $this->bucketlistItems->add($bucketlistItem);
            $bucketlistItem->setBucketList($this);
        }

        return $this;
    }

    public function removeBucketlistItem(BucketlistItem $bucketlistItem): self
    {
        if ($this->bucketlistItems->removeElement($bucketlistItem)) {
            // set the owning side to null (unless already changed)
            if ($bucketlistItem->getBucketList() === $this) {
                $bucketlistItem->setBucketList(null);
            }
        }

        return $this;
    }

    public function getShareBucketlist(): ?ShareBucketlist
    {
        return $this->shareBucketlist;
    }

    public function setShareBucketlist(ShareBucketlist $shareBucketlist): static
    {
        // set the owning side of the relation if necessary
        if ($shareBucketlist->getBucketlist() !== $this) {
            $shareBucketlist->setBucketlist($this);
        }

        $this->shareBucketlist = $shareBucketlist;

        return $this;
    }
}
