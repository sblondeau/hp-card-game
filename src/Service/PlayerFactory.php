<?php

namespace App\Service;

use App\Entity\Player;

class PlayerFactory
{
    public function create(string $name, array $cards, array $actionnables = []): Player
    {
        $player = new Player();
        $player->setName($name);
        foreach ($cards as $card) {
            $player->addCard($card);
        }

        foreach ($actionnables as $actionnable) {
            $player->addAction($actionnable);
        }

        return $player;
    }
}
