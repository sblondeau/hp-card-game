<?php

namespace App\Service;

use App\Service\Actions\Dragon;
use App\Service\Actions\HealPotion;
use App\Service\Actions\Troll;
use SplObjectStorage;

class ArenaFactory
{
    public function __construct(private CardFactory $cardFactory, private PlayerFactory $playerFactory)
    {
    }

    public function create(): SplObjectStorage
    {
        $harry = $this->cardFactory->create('Harry', 100, 50);
        $ron = $this->cardFactory->create('Ron', 100, 50);
        $hermione = $this->cardFactory->create('Hermione', 80, 70);
        $goyle = $this->cardFactory->create('Goyle', 110, 40);
        $draco = $this->cardFactory->create('Draco', 90, 60);
        $grabb = $this->cardFactory->create('Grabb', 120, 35);

        $heal = new HealPotion();
        $dragon = new Dragon();
        $player1 = $this->playerFactory->create(name: 'player1', cards: [$draco, $goyle, $grabb], actionnables: [$heal, $dragon]);
        $heal = new HealPotion();
        $troll = new Troll();
        $player2 = $this->playerFactory->create(name: 'player2', cards: [$harry, $ron, $hermione], actionnables: [$heal, $troll]);

        $arena = new SplObjectStorage();
        $arena->attach($player1);
        $arena->attach($player2);
        
        return $arena;
    }
}