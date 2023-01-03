<?php

namespace App\Service\Actions;

use App\Entity\Card;
use App\Service\PlayerSwitcher;
use Doctrine\Common\Collections\Collection;

interface Actionnable
{
    public function action(): void;
    public function setAttacker(?Card $card): static;
    public function getAttacker(): ?Card;
    public function setTarget(?Card $card): static;
    public function getTarget(): ?Card;
    public function getPlayerSwitcher(): ?PlayerSwitcher;
    public function setPlayerSwitcher(?PlayerSwitcher $playerSwitcher): static;
    public function isValidTarget(): bool;
    public function getPossibleTargets(): ?Collection;
    public function isValidAttacker(): bool;
    public function getCost(): int;
}