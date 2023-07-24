<?php

namespace App\Entity;

use App\Repository\BucketlistItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BucketlistItemRepository::class)]
class BucketlistItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $display = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'bucketlistItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bucketlist $bucketList = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date = null;

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

    public function getBucketList(): ?Bucketlist
    {
        return $this->bucketList;
    }

    public function setBucketList(?Bucketlist $bucketList): self
    {
        $this->bucketList = $bucketList;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }
}
