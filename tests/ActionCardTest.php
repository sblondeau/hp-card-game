<?php

namespace App\Tests;

use App\Entity\Card;
use App\Entity\Player;
use App\Service\Actioner;
use App\Service\Actions\Actionnable;
use App\Service\Actions\Dragon;
use App\Service\Actions\Fight;
use App\Service\Actions\HealPotion;
use App\Service\Actions\Troll;
use App\Service\Factory\ActionerFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\PlayerSwitcher;
use App\Service\Factory\ArenaFactory;
use SplObjectStorage;

class ActionCardTest extends KernelTestCase
{
    private Actioner $actioner;
    
    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $arenaFactory = static::getContainer()->get(ArenaFactory::class);

        $actionerFactory = new ActionerFactory(); 
        $this->actioner = $actionerFactory->create($arenaFactory);
    }

    public function testPlayerActionSwitch(): void
    {
        $currentPlayer = $this->actioner->getPlayerSwitcher()->getCurrentPlayer();
        $drago = $currentPlayer->getCards()[0];
        $drago->setLife(20);  
        $this->actioner->setAttacker($drago);
        $this->actioner->setActionnable($currentPlayer->getActions()[0]);
        $this->assertInstanceOf(HealPotion::class, $currentPlayer->getActions()[0]);

        $this->actioner->setTarget($drago);
        $this->assertNull($currentPlayer->getActions()[0]);
        $this->assertInstanceOf(Dragon::class, $currentPlayer->getActions()[1]);


    }
}
