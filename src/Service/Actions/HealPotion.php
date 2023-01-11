<?php

namespace App\Service\Actions;

use Doctrine\Common\Collections\Collection;

class HealPotion extends AbstractAction
{
    public const HEAL = 5;
    protected int $cost = 5;    
    protected string $name = 'Heal';

    protected function applyEffect(): void
    {
        $this->getTarget()->setLife($this->getTarget()->getLife() + self::HEAL);
    }

    public function getPossibleTargets(): ?Collection
    {
        $targets = $this->getPlayerSwitcher()->getCurrentPlayer()->getCards();
        $possibleTargets = $targets->filter(function($target) {
            return $target->getLife() < $target->getMaxLife();
        });

        return $possibleTargets;
    }

    public function getDescription(): string
    {
        return 'Soigne, ' . self::HEAL . ' points de vie';
    }
}
