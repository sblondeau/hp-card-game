<?php

namespace App\Service\Actions;

use Doctrine\Common\Collections\Collection;

class Dragon extends AbstractAction
{
    public const DAMAGE = 10;
    protected int $cost = 25;
    protected string $name = 'Dragon';
    protected ?string $image = 'dragon.jpg';

    protected function applyEffect(): void
    {
        $player = $this->getTarget()->getPlayer();

        foreach($player->getCards() as $target) {
            $target->setLife($target->getLife() - self::DAMAGE);
        }
    }

    public function getPossibleTargets(): ?Collection
    {
        $targets = $this->getPlayerSwitcher()->getOtherPlayersCards();

        return $targets;
    }

    public function getDescription(): string
    {
        return 'Un dragon crache du feu et inflige ' . self::DAMAGE . ' points de dommage à tous les personnages du joueur sélectionné';
    }
}
