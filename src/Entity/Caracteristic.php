<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\CaracteristicModifier;
use App\Repository\CaracteristicRepository;
use SplObserver;

#[ORM\Entity(repositoryClass: CaracteristicRepository::class)]
class Caracteristic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $intelligence = 0;

    #[ORM\OneToOne(mappedBy: 'caracteristic', cascade: ['persist', 'remove'])]
    private ?Card $card = null;

    #[ORM\OneToOne(mappedBy: 'caracteristic', cascade: ['persist', 'remove'])]
    private ?CaracteristicModifier $intelligenceModifier = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntelligence(): ?int
    {
        $durationModifier = 1;
        if($this->getIntelligenceModifier()?->getDuration() !== null && $this->getIntelligenceModifier()->getDuration()<= 0) {
            $durationModifier = 0;
        }

        return $this->intelligence + $this->getIntelligenceModifier()?->getQuantity() * $durationModifier;
    }

    public function setIntelligence(int $intelligence): self
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): self
    {
        // unset the owning side of the relation if necessary
        if ($card === null && $this->card !== null) {
            $this->card->setCaracteristic(null);
        }

        // set the owning side of the relation if necessary
        if ($card !== null && $card->getCaracteristic() !== $this) {
            $card->setCaracteristic($this);
        }

        $this->card = $card;

        return $this;
    }

    public function getIntelligenceModifier(): ?CaracteristicModifier
    {
        return $this->intelligenceModifier;
    }

    public function setIntelligenceModifier(?CaracteristicModifier $intelligenceModifier): self
    {
        // unset the owning side of the relation if necessary
        if ($intelligenceModifier === null && $this->intelligenceModifier !== null) {
            $this->intelligenceModifier->setCaracteristic(null);
        }

        // set the owning side of the relation if necessary
        if ($intelligenceModifier !== null && $intelligenceModifier->getCaracteristic() !== $this) {
            $intelligenceModifier->setCaracteristic($this);
        }

        $this->intelligenceModifier = $intelligenceModifier;

        return $this;
    }


}
