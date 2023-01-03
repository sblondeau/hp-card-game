<?php

namespace App\Service\Actions;

use App\Entity\Card;
use App\Service\PlayerSwitcher;
use Doctrine\Common\Collections\Collection;

abstract class AbstractAction implements Actionnable, Selectionnable
{
    private ?Card $attacker = null;
    private ?Card $target = null;
    private ?PlayerSwitcher $playerSwitcher = null;
    protected string $name;
    protected string $identifier;
    protected int $cost = 0;

    public function __construct()
    {
        $this->identifier = uniqid();
    }

    public function action(): void
    {
        $this->applyEffect();
        $this->applyCost();
    }

    abstract protected function applyEffect(): void;

    protected function applyCost(): void
    {
        $this->getAttacker()->setMagic($this->getAttacker()->getMagic() - $this->getCost());
    }

    /**
     * Get the value of attacker
     */
    public function getAttacker(): ?Card
    {
        return $this->attacker;
    }

    /**
     * Set the value of attacker
     */
    public function setAttacker(?Card $attacker): static
    {
        $this->attacker = $attacker;

        return $this;
    }


    /**
     * Get the value of target
     */
    public function getTarget(): ?Card
    {
        return $this->target;
    }

    /**
     * Set the value of target
     */
    public function setTarget(?Card $target): static
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get the value of playerSwitcher
     */
    public function getPlayerSwitcher(): ?PlayerSwitcher
    {
        return $this->playerSwitcher;
    }

    /**
     * Set the value of playerSwitcher
     */
    public function setPlayerSwitcher(?PlayerSwitcher $playerSwitcher): static
    {
        $this->playerSwitcher = $playerSwitcher;

        return $this;
    }

    abstract public function getPossibleTargets(): ?Collection;

    public function isValidTarget(): bool 
    {
        return $this->getPossibleTargets()->contains($this->getTarget());
    }

    public function isValidAttacker(): bool
    {
        return $this->getAttacker()->getMagic() >= $this->getCost();
    }

    /**
     * Get the value of identifier
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Get the value of name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the value of cost
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * Set the value of cost
     */
    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }
}
