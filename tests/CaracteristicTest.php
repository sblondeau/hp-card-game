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


    
}