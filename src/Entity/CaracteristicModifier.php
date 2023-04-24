<?php

namespace App\Entity;

use SplSubject;
use SplObserver;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CaracteristicModifierRepository;

#[ORM\Entity(repositoryClass: CaracteristicModifierRepository::class)]
class CaracteristicModifier implements SplObserver
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\OneToOne(inversedBy: 'caracteristicModifier', cascade: ['persist', 'remove'])]
    private ?Caracteristic $caracteristic = null;

    public function __construct(int $quantity, ?int $duration = null)
    {
        $this->quantity = $quantity;
        $this->duration = $duration;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCaracteristic(): ?Caracteristic
    {
        return $this->caracteristic;
    }

    public function setCaracteristic(?Caracteristic $caracteristic): self
    {
        $this->caracteristic = $caracteristic;

        return $this;
    }

    // pattern Observer
    public function update(SplSubject $subject): void
    {
        // dd($subject);
        $this->setDuration($this->getDuration() - 1);
    }
}
