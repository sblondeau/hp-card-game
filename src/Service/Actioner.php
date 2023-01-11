<?php

namespace App\Service;

use App\Entity\Card;
use App\Service\Actions\Actionnable;
use App\Service\Actions\Fight;
use App\Service\Actions\Selectionnable;
use Exception;

class Actioner
{    
    private ?Card $attacker = null;
    private ?Card $target = null;
    private ?Actionnable $actionnable = null;
    private ?PlayerSwitcher $playerSwitcher = null;

    /**
     * Get the value of playerSwitcher
     */
    public function getPlayerSwitcher(): PlayerSwitcher
    {
        return $this->playerSwitcher;
    }

    /**
     * Set the value of playerSwitcher
     */
    public function setPlayerSwitcher(PlayerSwitcher $playerSwitcher): self
    {
        $this->playerSwitcher = $playerSwitcher;

        return $this;
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
    public function setAttacker(?Card $attacker): self
    {
        if($attacker !== null && !$this->getPlayerSwitcher()->getCurrentPlayer()->getCards()->contains($attacker)) {
            throw new Exception('Wrong player');
        }

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
    public function setTarget(?Card $target): self
    {
        if(!$this->getActionnable() instanceof Actionnable) {
            throw new Exception('You have to select an action first');
        }

        $this->actionnable->setTarget($target);

        if(!$this->actionnable->isValidAttacker()) {
            throw new Exception('Pas assez de magie');
        }

        if(!$this->actionnable->isValidTarget()) {
            throw new Exception('The target card is not valid');
        }

        $this->target = $target;

        $this->actionnable->action();

        $this->reset();

        $this->getPlayerSwitcher()->switch();

        return $this;
    }

    private function reset() 
    {
        $this->attacker = null;
        $this->actionnable = null;
        $this->target = null;
    }

    /**
     * Get the value of actionnable
     */
    public function getActionnable(): ?Actionnable
    {
        return $this->actionnable;
    }

    /**
     * Set the value of actionnable
     */
    public function setActionnable(?Selectionnable $actionnable): self
    {
        if(!$actionnable instanceof Actionnable) {
            throw new Exception('Vous devez choisir une carte action'); 
        }

        if(!$this->getAttacker() instanceof Card) {
            throw new Exception('You have to select an attacker card first');
        }

        if(!$actionnable instanceof Fight && !$this->getPlayerSwitcher()->getCurrentPlayer()->getActions()->contains($actionnable)) {
            throw new Exception('The action card is not valid');
        }

        $actionnable->setAttacker($this->attacker);
        $actionnable->setPlayerSwitcher($this->playerSwitcher);

        if($actionnable->getPossibleTargets()->isEmpty()) {
            throw new Exception('The card is not usable');
        }

        $this->actionnable = $actionnable;

        return $this;
    }
}
