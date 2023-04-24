<?php

namespace App\Service\Factory;

use App\Entity\Arena;
use App\Service\Actions\Dragon;
use App\Service\Actions\HealPotion;
use App\Service\Actions\IntelligencePotion;
use App\Service\Actions\Troll;
use App\Service\Factory\ArenaFactoryInterface;
use SplObjectStorage;

class ArenaFactory implements ArenaFactoryInterface
{
    public function __construct(private CardFactory $cardFactory, private PlayerFactory $playerFactory)
    {
    }

    public function create(): Arena
    {
        $harry = $this->cardFactory->create('Harry', 100, 50);
        $ron = $this->cardFactory->create('Ron', 100, 50, 'ron.jpg');
        $hermione = $this->cardFactory->create('Hermione', 80, 70);
        $goyle = $this->cardFactory->create('Goyle', 110, 40);
        $draco = $this->cardFactory->create('Draco', 90, 60, 'draco.jpg');
        $grabb = $this->cardFactory->create('Grabb', 120, 35);

        $healPotion = new HealPotion();
        $dragon = new Dragon();

        $player1 = $this->playerFactory->create(
            name: 'player1',
            cards: [$draco, $goyle, $grabb],
            actionnables: [$healPotion, $dragon]
        );
        $healPotion = new HealPotion();
        $troll = new Troll();
        $player2 = $this->playerFactory->create(
            name: 'player2',
            cards: [$harry, $ron, $hermione],
            actionnables: [$healPotion, $troll]
        );

        $arena = new Arena();
        $arena->addPlayer($player1);
        $arena->addPlayer($player2);

        return $arena;
    }
}
