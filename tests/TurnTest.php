<?php

namespace App\Tests;

use App\Entity\Card;
use App\Entity\Player;
use App\Service\Actions\Dragon;
use App\Service\Actions\HealPotion;
use App\Service\Actions\Troll;
use App\Service\Factory\ArenaFactory;
use App\Service\PlayerSwitcher;
use SplObjectStorage;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TurnTest extends KernelTestCase
{
    private PlayerSwitcher $playerSwitcher;
    private Card $drago;
    private Player $player1;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->playerSwitcher = static::getContainer()->get(PlayerSwitcher::class);
        $arenaFactory = static::getContainer()->get(ArenaFactory::class);

        $arena = $arenaFactory->create();
        $players = new SplObjectStorage();
        foreach($arena->getPlayers() as $player) {
            $players->attach($player);
        }

        $this->playerSwitcher->setPlayers($players);
        $this->player1 = $this->playerSwitcher->getCurrentPlayer();
        $this->drago = $this->player1->getCards()[0];

    }

    public function testOneTurn(): void
    {
        $this->assertSame('player1', $this->playerSwitcher->getCurrentPlayer()->getName());
    }

    public function testTwoTurn(): void
    {
        $this->assertSame('player1', $this->playerSwitcher->getCurrentPlayer()->getName());
        $this->playerSwitcher->switch();
        $this->assertSame('player2', $this->playerSwitcher->getCurrentPlayer()->getName());
    }

    public function testThreeTurn(): void
    {
        $this->assertSame('player1', $this->playerSwitcher->getCurrentPlayer()->getName());
        $this->playerSwitcher->switch();
        $this->assertSame('player2', $this->playerSwitcher->getCurrentPlayer()->getName());
        $this->playerSwitcher->switch();
        $this->assertSame('player1', $this->playerSwitcher->getCurrentPlayer()->getName());
    }

    public function testPlayerActionCard(): void
    {
        $this->assertCount(2, $this->playerSwitcher->getCurrentPlayer()->getActions());
        $this->playerSwitcher->getCurrentPlayer()->addAction(new Dragon());
        $this->assertCount(3, $this->playerSwitcher->getCurrentPlayer()->getActions());
        $this->playerSwitcher->getCurrentPlayer()->addAction(new Troll());
        $this->assertCount(4, $this->playerSwitcher->getCurrentPlayer()->getActions());
        $this->playerSwitcher->switch();
        $this->assertCount(2, $this->playerSwitcher->getCurrentPlayer()->getActions());
        $this->playerSwitcher->switch();
        $this->assertCount(4, $this->playerSwitcher->getCurrentPlayer()->getActions());
    }


}
