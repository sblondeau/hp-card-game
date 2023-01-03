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
        $targets = $this->getPlayerSwitcher()->getNextPlayer()->getCards();

        return $targets;
    }

    public function getIdentifier(): string
    {
        return 'fight';
    }
}
