<?php

namespace App\Entity;

use App\Entity\Card;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlayerRepository;
use App\Service\Actions\Actionnable;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $actionNumber = 3;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Card::class)]
    private Collection $cards;

    private Collection $actions;

    #[ORM\ManyToMany(targetEntity: Arena::class, mappedBy: 'players')]
    private Collection $arenas;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->actions = new ArrayCollection();
        $this->arenas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Card>
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards->add($card);
            $card->setPlayer($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getPlayer() === $this) {
                $card->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Action>
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(Actionnable $action): self
    {
        $this->actions->add($action);

        return $this;
    }
    public function removeAction(Actionnable $action): self
    {
        $this->actions->removeElement($action);

        return $this;
    }

    /**
     * @return Collection<int, Arena>
     */
    public function getArenas(): Collection
    {
        return $this->arenas;
    }

    public function addArena(Arena $arena): self
    {
        if (!$this->arenas->contains($arena)) {
            $this->arenas->add($arena);
            $arena->addPlayer($this);
        }

        return $this;
    }

    public function removeArena(Arena $arena): self
    {
        if ($this->arenas->removeElement($arena)) {
            $arena->removePlayer($this);
        }

        return $this;
    }
}
