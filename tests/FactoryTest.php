<?php

namespace App\Tests;

use App\Entity\Card;
use App\Entity\Player;
use App\Service\CardFactory;
use App\Service\PlayerFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FactoryTest extends KernelTestCase
{
    public function setUp(): void
    {
        $cardFactory = static::getContainer()->get(CardFactory::class);
        $this->playerFactory = static::getContainer()->get(PlayerFactory::class);

        $this->drago = $cardFactory->create('Drago', 20, 10);
        $this->harry = $cardFactory->create('Harry', 10, 20);
        $this->ron = $cardFactory->create('Ron', 10, 20);
    }

    public function testCreateFighter(): void
    {
        $kernel = self::bootKernel();
        $cardFactory = static::getContainer()->get(CardFactory::class);

        $harry = $cardFactory->create('Harry', 10, 10);

        $this->assertInstanceOf(Card::class, $harry);
        $this->assertSame(10, $harry->getLife());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }

    public function testCreateDeck(): void
    {
        $player1 = new Player();
        $player2 = new Player();
        $player1->addCard($this->harry);
        $player1->addCard($this->ron);
        $player2->addCard($this->drago);

        $this->assertCount(2, $player1->getCards());
        $this->assertCount(1, $player2->getCards());
    }

    public function testCreatePlayer(): void
    {
        $player2 = $this->playerFactory->create('player2', [$this->drago]);
        $player1 = $this->playerFactory->create('player1', [$this->harry, $this->ron]);

        $this->assertCount(2, $player1->getCards());
        $this->assertCount(1, $player2->getCards());

        $player2->addCard($this->harry); 
        $this->assertCount(2, $player2->getCards());

        $this->assertSame('player2', $player2->getName());
    }
}
