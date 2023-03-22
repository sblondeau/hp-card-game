<?php

namespace App\Service\Factory;

use App\Entity\Card;

class CardFactory
{
    public function create(string $name, int $life, int $magic, string $image='')
    {
        $card = new Card();
        $card->setName($name)->setMaxLife($life)->setLife($life)->setMagic($magic);

        return $card;
    }
}