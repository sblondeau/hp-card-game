<?php

namespace App\Service\Factory;

use App\Entity\Caracteristic;
use App\Entity\Card;

class CardFactory
{
    public function create(string $name, int $life, int $magic, ?string $image=null)
    {
        $card = new Card();
        $card->setName($name)->setMaxLife($life)->setLife($life)->setMagic($magic)->setImage($image);
        $card->setCaracteristic($this->createCaracteristic());

        return $card;
    }

    public function createCaracteristic($intelligence = 10)
    {
        $caracteristic = new Caracteristic();
        $caracteristic->setIntelligence($intelligence);

        return $caracteristic;
    }
}