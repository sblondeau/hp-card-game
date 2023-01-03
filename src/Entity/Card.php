<?php

namespace App\Entity;

use App\Repository\CardRepository;
use App\Service\Actions\Selectionnable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardRepository::class)]
class Card implements Selectionnable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    private string $identifier;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $life = null;

    #[ORM\Column]
    private ?int $magic = null;

    #[ORM\ManyToOne(inversedBy: 'cards')]
    private ?Player $player = null;

    #[ORM\Column]
    private ?int $maxLife = null;

    public function __construct()
    {
        $this->identifier = uniqid();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLife(): ?int
    {
        return $this->life;
    }

    public function setLife(int $life): self
    {
        if($life > $this->getMaxLife()) {
            $life = $this->getMaxLife();
        }
        
        $this->life = $life;

        return $this;
    }

    public function getMagic(): ?int
    {
        return $this->magic;
    }

    public function setMagic(int $magic): self
    {
        $this->magic = $magic;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get the value of identifier
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getMaxLife(): ?int
    {
        return $this->maxLife;
    }

    public function setMaxLife(int $maxLife): self
    {
        $this->maxLife = $maxLife;

        return $this;
    }
}
