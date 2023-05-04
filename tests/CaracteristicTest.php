<?php

namespace App\Tests;

use App\Entity\Card;
use SplObjectStorage;
use App\Entity\Player;
use App\Service\Actioner;
use App\Entity\Carateristic;
use App\Entity\Caracteristic;
use App\Service\Actions\Troll;
use App\Service\Actions\Dragon;
use App\Service\PlayerSwitcher;
use App\Entity\CardCaracteristic;
use App\Service\Actions\HealPotion;
use App\Entity\CaracteristicModifier;
use App\Service\Actions\Fight;
use App\Service\Actions\IntelligencePotion;
use App\Service\Factory\ArenaFactory;
use App\Service\Factory\PlayerFactory;
use App\Service\Factory\ActionerFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CaracteristicTest extends KernelTestCase
{
    private Card $harry;
    private Card $drago;
    private Card $ron;
    private Caracteristic $caracteristic;
    private Actioner $actioner;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $arenaFactory = static::getContainer()->get(ArenaFactory::class);

        $actionerFactory = new ActionerFactory(); 
        $this->actioner = $actionerFactory->create($arenaFactory);
        $currentPlayer = $this->actioner->getPlayerSwitcher()->getCurrentPlayer();
        $this->drago = $currentPlayer->getCards()[0];
        $this->harry = $this->actioner->getPlayerSwitcher()->getNextPlayer()->getCards()[0];
    }

    public function testIntelligence(): void
    {
        $this->assertSame(10, $this->drago->getCaracteristic()->getIntelligence());
    }    
    
    public function testOtherIntelligence(): void
    {
        $cardCaracteristic = new Caracteristic();
        $cardCaracteristic->setCard($this->drago);
        $cardCaracteristic->setIntelligence(15);
        $this->assertSame(15, $this->drago->getCaracteristic()->getIntelligence());
        $cardCaracteristic->setIntelligence(20);
        $this->assertSame(20, $this->drago->getCaracteristic()->getIntelligence());
    }
    
    public function testIntelligenceModifier(): void
    {
        $intelligenceModifier = new CaracteristicModifier(5);
        $cardCaracteristic = $this->drago->getCaracteristic();
        $cardCaracteristic->setIntelligenceModifier($intelligenceModifier);

        $this->assertSame(15, $this->drago->getCaracteristic()->getIntelligence());

        $intelligenceModifier = new CaracteristicModifier(-8);
        $cardCaracteristic->setIntelligenceModifier($intelligenceModifier);
        $this->assertSame(2, $this->drago->getCaracteristic()->getIntelligence());
    }

    public function testIntelligenceTimeModifier(): void
    {
        // 2 turn
        $intelligenceModifier = new CaracteristicModifier(5, 2);
        $cardCaracteristic = $this->drago->getCaracteristic();
        $cardCaracteristic->setIntelligenceModifier($intelligenceModifier);
        $this->assertSame(15, $this->drago->getCaracteristic()->getIntelligence());

        $this->drago->getCaracteristic()->getIntelligenceModifier()->setDuration(0);

        $this->assertSame(10, $this->drago->getCaracteristic()->getIntelligence());
    }

    public function testIntelligenceTimeModifierWithSwitch(): void
    {
        $intelligenceModifier = new CaracteristicModifier(5, 4);
        $cardCaracteristic = $this->drago->getCaracteristic();
        $cardCaracteristic->setIntelligenceModifier($intelligenceModifier);
        // P1 4
        $this->assertSame(15, $this->drago->getCaracteristic()->getIntelligence());
        $this->actioner->getPlayerSwitcher()->switch();

        // P2
        $this->actioner->getPlayerSwitcher()->switch();
        //P1 2
        $this->assertSame(15, $this->drago->getCaracteristic()->getIntelligence());
        $this->actioner->getPlayerSwitcher()->switch();

        $this->actioner->getPlayerSwitcher()->switch();
        // P1 again : turn 2. End its turn, then modifier timer is finished 0

        $this->assertSame(10, $this->drago->getCaracteristic()->getIntelligence());
        $this->actioner->getPlayerSwitcher()->switch();
        $this->assertSame(10, $this->drago->getCaracteristic()->getIntelligence());
    }

    public function testIntelligencePotion(): void
    {
        $this->assertSame(10, $this->drago->getCaracteristic()->getIntelligence());

        $intelligencePotion = new IntelligencePotion();
        $this->drago->getPlayer()->addAction($intelligencePotion);
        $this->actioner->setAttacker($this->drago);
        $this->actioner->setActionnable($intelligencePotion);
        $this->actioner->setTarget($this->drago);

        $this->assertSame(15, $this->drago->getCaracteristic()->getIntelligence());
        $this->actioner->getPlayerSwitcher()->switch()->switch()->switch();
        $this->assertSame(15, $this->drago->getCaracteristic()->getIntelligence());
        $this->actioner->getPlayerSwitcher()->switch();
        $this->assertSame(10, $this->drago->getCaracteristic()->getIntelligence());
    }
    
    public function testIntelligenceMagicBonus(): void
    {
        $this->drago->setMagic(10);
        $this->drago->getCaracteristic()->setIntelligence(10);
        $this->assertSame(15, $this->drago->getMagic());
        $this->drago->getCaracteristic()->setIntelligence(11);
        $this->assertSame(15, $this->drago->getMagic());
        $this->drago->getCaracteristic()->setIntelligence(12);
        $this->assertSame(16, $this->drago->getMagic());
    }

    public function testStrength(): void
    {
        $this->drago->getCaracteristic()->setStrength(20);
        $this->assertSame(20, $this->drago->getCaracteristic()->getStrength());
    }

    // strength add damage to fight  
    public function testAttackWithStrength(): void
    {
        $actioner = new Actioner();
        $actioner->setPlayerSwitcher($this->actioner->getPlayerSwitcher());
        $fight = new Fight();
        $actioner->setAttacker($this->drago);
        $actioner->setActionnable($fight);
        $actioner->setTarget($this->harry);
        $this->assertSame(95, $this->harry->getLife());

        $this->drago->getCaracteristic()->setStrength(2);

        $this->actioner->getPlayerSwitcher()->switch();
        $actioner->setAttacker($this->drago);
        $actioner->setActionnable($fight);
        $actioner->setTarget($this->harry);
        $this->assertSame(88, $this->harry->getLife());
    }

}