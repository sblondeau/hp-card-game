<?php

namespace App\Service\Actions;

use App\Entity\CaracteristicModifier;
use Doctrine\Common\Collections\Collection;

class IntelligencePotion extends AbstractAction
{
    public const INTELLIGENCE_BONUS = 5;
    public const DURATION = 5;
    protected int $cost = 0;    
    protected string $name = 'Intelligence Potion';
    protected ?string $image = 'intelligencePotion.png';

    protected function applyEffect(): void
    {
        $this->getTarget()->getCaracteristic()->setIntelligenceModifier(new CaracteristicModifier(self::INTELLIGENCE_BONUS, self::DURATION));
    }

    public function getPossibleTargets(): ?Collection
    {
        $possibleTargets = $this->getPlayerSwitcher()->getCurrentPlayer()->getCards();

        return $possibleTargets;
    }

    public function getDescription(): string
    {
        return 'Ajouter, ' . self::INTELLIGENCE_BONUS . ' points d\'intelligence pendant ' . self::DURATION - 1 . ' tours';
    }
}
