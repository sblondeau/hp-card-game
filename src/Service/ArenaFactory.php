<?php

namespace App\Service;

use App\Entity\Card;
use App\Entity\Player;

class ArenaFactory
{
    public function __construct(private CardFactory $cardFactory, private PlayerFactory $playerFactory)
    {
    }

    public function create(): array
    {
        $harry = $this->cardFactory->create('Harry', 100, 50);
        $ron = $this->cardFactory->create('Ron', 100, 50);
        $goyle = $this->cardFactory->create('Goyle', 110, 40);
        $draco = $this->cardFactory->create('Draco', 90, 60);
        $grabb = $this->cardFactory->create('Grabb', 120, 35);
        
        $player1 = $this->playerFactory->create(name: 'player1', cards: [$draco, $goyle, $grabb]);
        $player2 = $this->playerFactory->create(name: 'player1', cards: [$harry, $ron]);

        return [$player1, $player2];
    }
}