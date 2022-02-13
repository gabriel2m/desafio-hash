<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Hold the data returned by the "hash" route on the AvatoTestCommand
 */
#[Assert\EnableAutoMapping]
#[ORM\Entity(repositoryClass: ResultRepository::class)]
#[ORM\Table(name: 'results')]
class Result
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['show'])]
    private $batch;

    #[ORM\Column(type: 'integer')]
    #[Groups(['show'])]
    private $block;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['show'])]
    private $string;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['show'])]
    private $key;

    #[ORM\Column(type: 'string', length: 255)]
    private $hash;

    #[ORM\Column(type: 'integer')]
    private $attempts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBatch(): ?\DateTimeInterface
    {
        return $this->batch;
    }

    public function setBatch(\DateTimeInterface $batch): self
    {
        $this->batch = $batch;

        return $this;
    }

    public function getBlock(): ?int
    {
        return $this->block;
    }

    public function setBlock(int $block): self
    {
        $this->block = $block;

        return $this;
    }

    public function getString(): ?string
    {
        return $this->string;
    }

    public function setString(string $string): self
    {
        $this->string = $string;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): self
    {
        $this->attempts = $attempts;

        return $this;
    }
}
