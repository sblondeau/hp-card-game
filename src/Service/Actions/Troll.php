<?php

namespace App\Service\Actions;

use Doctrine\Common\Collections\Collection;

class Troll extends AbstractAction
{
    public const DAMAGE = 15;
    protected int $cost = 15;
    protected string $name = 'Fight';

    protected function applyEffect(): void
    {
        $this->getTarget()->setLife($this->getTarget()->getLife() - self::DAMAGE);
    }

    public function getPossibleTargets(): ?Collection
    {
        $targets = $this->getPlayerSwitcher()->getOtherPlayersCards();

        return $targets;
    }

    public function getIdentifier(): string
    {
        return 'troll';
    }
}
