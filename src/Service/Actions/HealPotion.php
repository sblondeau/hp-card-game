<?php

namespace App\Service\Actions;

use Doctrine\Common\Collections\Collection;

class HealPotion extends AbstractAction
{
    public const HEAL = 5;
    public const MAGIC = 5;

    protected string $name = 'Heal';

    public function getDescription(): string
    {
        return 'Potion de soin';
    }

    public function action(): void
    {
        $this->getTarget()->setLife($this->getTarget()->getLife() + self::HEAL);
        $this->getAttacker()->setMagic($this->getAttacker()->getMagic() - self::MAGIC);
    }

    public function getPossibleTargets(): Collection
    {
        $targets = $this->getPlayerSwitcher()->getCurrentPlayer()->getCards();

        return $targets;
    }
}
