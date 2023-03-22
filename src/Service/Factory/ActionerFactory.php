<?php

namespace App\Service\Factory;

use App\Service\Actioner;

class ActionerFactory
{
    public function __construct(private Actioner $actioner = new Actioner())
    {
    }

    public function create(ArenaFactoryInterface $arenaFactory): Actioner
    {
        $arena = $arenaFactory->create();

        foreach ($arena->getPlayers() as $player) {
            $this->actioner->getPlayerSwitcher()->addPlayer($player);
        }

        return $this->actioner;
    }
}
