<?php

namespace App\Tests;

use App\Service\Actioner;
use App\Service\Actions\Fight;
use App\Service\Actions\HealPotion;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\PlayerSwitcher;
use App\Service\ArenaFactory;

class ActionerTest extends KernelTestCase
{
    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->playerSwitcher = static::getContainer()->get(PlayerSwitcher::class);
        $arenaFactory = static::getContainer()->get(ArenaFactory::class);

        $players = $arenaFactory->create();
        $this->playerSwitcher->setPlayers($players);
        $this->player1 = $this->playerSwitcher->getCurrentPlayer();
        $this->drago = $this->player1->getCards()[0];
        $this->playerSwitcher->switch();
        $this->player2 = $this->playerSwitcher->getCurrentPlayer();
        $this->harry = $this->player2->getCards()[0];
    }

    public function testPlayCard(): void
    {
        $actioner = new Actioner();
        $actioner->setPlayerSwitcher($this->playerSwitcher);
        $fight = new Fight();
        $actioner->setAttacker($this->harry);
        $actioner->setActionnable($fight);
        $actioner->setTarget($this->drago);
        $this->assertSame(85, $this->drago->getLife());
    }
    
    public function testBadAttacker(): void
    {
        $this->expectExceptionMessage('Wrong player');
        $actioner = new Actioner();
        $actioner->setPlayerSwitcher($this->playerSwitcher);
        $actioner->setAttacker($this->drago);
    }

    public function testHeal(): void
    {
        $actioner = new Actioner();
        $actioner->setPlayerSwitcher($this->playerSwitcher);
        $heal = $this->player2->getActions()[0];
        $this->harry->setLife(20);
        $actioner->setAttacker($this->harry);
        $actioner->setActionnable($heal);
        $actioner->setTarget($this->harry);

        $this->assertSame(25, $this->harry->getLife());
    }  
    
    public function testHealBadTarget(): void
    {
        $this->expectExceptionMessage('The target card is not valid');
        $actioner = new Actioner();
        $actioner->setPlayerSwitcher($this->playerSwitcher);
        $heal = $this->player2->getActions()[0];
        $this->harry->setLife(20);
        $actioner->setAttacker($this->harry);
        $actioner->setActionnable($heal);
        $actioner->setTarget($this->drago);
    }

    public function testFight()
    {
        $actioner = new Actioner();
        $actioner->setPlayerSwitcher($this->playerSwitcher);
        $heal = $this->player2->getActions()[0];
        $fight = new Fight();

        $actioner->setAttacker($this->harry);
        $actioner->setActionnable($fight);
        $actioner->setTarget($this->drago);

        $this->assertSame(85, $this->drago->getLife());

        $actioner->setAttacker($this->drago);
        $actioner->setActionnable($fight);
        $actioner->setTarget($this->harry);
        $this->assertSame(95, $this->harry->getLife());

        $actioner->setAttacker($this->harry);
        $actioner->setActionnable($heal);
        $actioner->setTarget($this->harry);
        $this->assertSame(100, $this->harry->getLife());
    }

    public function testUseWrongActionnableHeal(): void
    {
        $this->expectExceptionMessage('The action card is not valid');

        $actioner = new Actioner();
        $actioner->setPlayerSwitcher($this->playerSwitcher);
        $this->playerSwitcher->setCurrentPlayer($this->player1);
        $heal = $this->player2->getActions()[0];  

        $actioner->setAttacker($this->drago);
        $actioner->setActionnable($heal);
        
        $actioner->setTarget($this->drago);
    } 

    public function testMagicConsumption(): void 
    {
        $actioner = new Actioner();
        $actioner->setPlayerSwitcher($this->playerSwitcher);
        $this->playerSwitcher->setCurrentPlayer($this->player1);
        $heal = $this->player1->getActions()[0];  
        $heal->setCost(5);
        $this->drago->setLife(50);
        $actioner->setAttacker($this->drago);
        $actioner->setActionnable($heal);
        $this->drago->setMagic(50);
        $this->assertSame(50, $this->drago->getMagic());
        $actioner->setTarget($this->drago);
        $this->assertSame(45, $this->drago->getMagic());
    }

    public function testNotEnoughMagic(): void 
    {
        $this->expectExceptionMessage('Pas assez de magie');

        $actioner = new Actioner();
        $actioner->setPlayerSwitcher($this->playerSwitcher);
        $this->playerSwitcher->setCurrentPlayer($this->player1);
        $heal = $this->player1->getActions()[0];  
        $heal->setCost(10);
        $this->drago->setLife(50);

        $actioner->setAttacker($this->drago);
        $actioner->setActionnable($heal);
        $this->drago->setMagic(4);
        $this->assertSame(4, $this->drago->getMagic());
        $actioner->setTarget($this->drago);
    }

    public function testMaxLife(): void 
    {
        $actioner = new Actioner();
        $actioner->setPlayerSwitcher($this->playerSwitcher);
        $this->playerSwitcher->setCurrentPlayer($this->player1);
        $heal = $this->player1->getActions()[0];  

        $actioner->setAttacker($this->drago);
        $this->drago->setMaxLife(100);
        $this->drago->setLife(98);
        
        $actioner->setActionnable($heal);
        $actioner->setTarget($this->drago);
        $this->assertSame(100, $this->drago->getLife());
    }

    public function testNoTargetForActionCard()
    {
        $this->expectExceptionMessage('The card is not usable');
        $actioner = new Actioner();
        $actioner->setPlayerSwitcher($this->playerSwitcher);
        $this->playerSwitcher->setCurrentPlayer($this->player1);
        $heal = $this->player1->getActions()[0];  

        $actioner->setAttacker($this->drago);
        $actioner->setActionnable($heal);

    }
}
