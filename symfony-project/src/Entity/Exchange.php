<?php

namespace App\Entity;

use App\Repository\ExchangeRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=ExchangeRepository::class)
 */
class Exchange
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
    private $originCurrency;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $destinyCurrency;

    /**
     * @ORM\Column(type="float")
     */
    private $originValue;

    /**
     * @ORM\Column(type="float")
     */
    private $finalCurrency;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function __toString()
    {
        return $this->getOriginCurrency().' to '.$this->getDestinyCurrency();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getOriginCurrency(): ?string
    {
        return $this->originCurrency;
    }

    public function setOriginCurrency(string $originCurrency): self
    {
        $this->originCurrency = $originCurrency;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDestinyCurrency()
    {
        return $this->destinyCurrency;
    }

    /**
     * @param mixed $destinyCurrency
     * @return Exchange
     */
    public function setDestinyCurrency($destinyCurrency)
    {
        $this->destinyCurrency = $destinyCurrency;
        return $this;
    }


    public function getOriginValue(): ?float
    {
        return $this->originValue;
    }

    public function setOriginValue(float $originValue): self
    {
        $this->originValue = $originValue;

        return $this;
    }

    public function getFinalCurrency(): ?float
    {
        return $this->finalCurrency;
    }

    public function setFinalCurrency(float $finalCurrency): self
    {
        $this->finalCurrency = $finalCurrency;

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
}
