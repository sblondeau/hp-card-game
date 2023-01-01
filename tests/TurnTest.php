<?php

namespace App\Tests;

use App\Service\ArenaFactory;
use App\Service\PlayerSwitcher;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TurnTest extends KernelTestCase
{
    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->playerSwitcher = static::getContainer()->get(PlayerSwitcher::class);

        $arenaFactory = static::getContainer()->get(ArenaFactory::class);

        $players = $arenaFactory->create();
        $this->playerSwitcher->setPlayers($players);
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
}
