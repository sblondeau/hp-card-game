<?php

namespace App\Service\Actions;

use Doctrine\Common\Collections\Collection;

class Fight extends AbstractAction
{
    public const DAMAGE = 5;
    protected int $cost = 5;
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

    public function getDescription(): string
    {
        return 'Attaque standard, ' . self::DAMAGE . ' points de dommage';
    }
}
