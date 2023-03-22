<?php

namespace App\Service\Factory;

use App\Entity\Arena;
use App\Repository\ArenaRepository;
use App\Repository\CardRepository;
use App\Repository\PlayerRepository;
use App\Service\Actions\Dragon;
use App\Service\Actions\HealPotion;
use App\Service\Actions\Troll;
use App\Service\Factory\ArenaFactoryInterface;
use PhpParser\Node\Scalar\MagicConst\Dir;

class ArenaOneFactory implements ArenaFactoryInterface
{

    public function __construct(
        private CardRepository $cardRepository,
        private PlayerRepository $playerRepository,
        private CardFactory $cardFactory,
        private PlayerFactory $playerFactory,
        private ArenaRepository $arenaRepository,
    ) {
    }

    public function create(): Arena
    {
        $player1 = $this->playerFactory->create(name: 'player1', cards: $this->setCardsPlayerOne(), actionnables: [...$this->actionPlayerOne()]);
        $player2 = $this->playerFactory->create(name: 'player2', cards: $this->setCardsPlayerTwo(), actionnables: [...$this->actionPlayerTwo()]);
        $this->playerRepository->save($player1, true);
        $this->playerRepository->save($player2, true);

        $arena = new Arena();
        $arena->setName('Arena One');
        $arena->addPlayer($player1);
        $arena->addPlayer($player2);
        $this->arenaRepository->save($arena, true);
        return $arena;
    }

    private function setCardsPlayerOne(): array
    {
        $cards = [
            $this->cardFactory->create('Harry', 100, 50, 'harry.jpg'),
            $this->cardFactory->create('Ron', 100, 50, 'ron.jpg'),
            $this->cardFactory->create('Hermione', 80, 70, 'hermione.jpg'),
            $this->cardFactory->create('Neville', 80, 70, 'neville.jpg'),
        ];
        $this->saveCards($cards);

        return $cards;
    }
    private function setCardsPlayerTwo(): array
    {
        $cards = [
            $this->cardFactory->create('Goyle', 110, 40),
            $this->cardFactory->create('Draco', 90, 60, 'draco.jpg'),
            $this->cardFactory->create('Grabb', 120, 35),
        ];

        $this->saveCards($cards);
        return $cards;
    }

    private function saveCards(array $cards)
    {
        foreach ($cards as $card) {
            $this->cardRepository->save($card, true);
        }
    }

    private function actionPlayerOne()
    {
        yield new HealPotion();
        yield new HealPotion();
        yield new Dragon();
    }
    private function actionPlayerTwo()
    {
        yield new HealPotion();
        yield new Troll();
        yield new Dragon();
    }
}
