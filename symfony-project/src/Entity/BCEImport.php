<?php

namespace App\Entity;

use App\Repository\BCEImportRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=BCEImportRepository::class)
 */
class BCEImport
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $baseCurrency;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="json")
     */
    private $rate = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getBaseCurrency(): ?string
    {
        return $this->baseCurrency;
    }

    public function setBaseCurrency(string $baseCurrency): self
    {
        $this->baseCurrency = $baseCurrency;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRate(): ?array
    {
        return $this->rate;
    }

    public function setRate(array $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
