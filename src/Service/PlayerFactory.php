<?php

namespace App\Service;

use App\Entity\Card;
use App\Entity\Player;

class PlayerFactory
{
    public function create(string $name, array $cards): Player
    {
        $player = new Player();
        $player->setName($name);
        foreach ($cards as $card) {
            $player->addCard($card);
        }

        return $player;
    }
}