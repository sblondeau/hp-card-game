<?php

namespace App\Service\Actions;

use Doctrine\Common\Collections\Collection;

class Troll extends AbstractAction
{
    public const DAMAGE = 15;
    protected int $cost = 15;
    protected string $name = 'Troll';

    protected function applyEffect(): void
    {
        $this->getTarget()->setLife($this->getTarget()->getLife() - self::DAMAGE);
    }

    public function getPossibleTargets(): ?Collection
    {
        $targets = $this->getPlayerSwitcher()->getOtherPlayersCards();

        return $targets;
    }

    public function getDescription(): string
    {
        return 'Vous envoyez un troll attaque avec son gourdin, ' . self::DAMAGE . ' points de dommage';
    }
}
