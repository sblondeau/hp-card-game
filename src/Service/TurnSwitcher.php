<?php

namespace App\Service;

use App\Entity\Card;
use App\Entity\Player;

class TurnSwitcher
{
    private array $players = [];

    public function add(array $players)
    {
        $this->players = $players;
    }

    public function current(): Player
    {
        return current($this->players);
    }

    public function next(): void
    {
        $last = next($this->players);
        if($last === false) {
            reset($this->players);
        }
    }

    public function getPlayers(): array
    {
        return $this->players;
    }
}