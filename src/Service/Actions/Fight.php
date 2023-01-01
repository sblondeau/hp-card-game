<?php

namespace App\Service\Actions;

use Doctrine\Common\Collections\Collection;

class Fight extends AbstractAction
{
    public const DAMAGE = 5;
    public const MAGIC = 5;
    protected string $name = 'Fight';

    public function action(): void
    {
        $this->getTarget()->setLife($this->getTarget()->getLife() - self::DAMAGE);
        $this->getAttacker()->setMagic($this->getAttacker()->getMagic() - self::MAGIC);
    }

    public function getPossibleTargets(): Collection
    {
        $targets = $this->getPlayerSwitcher()->getNextPlayer()->getCards();

        return $targets;
    }

    public function getIdentifier(): string
    {
        return 'fight';
    }
}
