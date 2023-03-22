<?php

namespace App\Service\Factory;

use App\Entity\Arena;

interface ArenaFactoryInterface
{
    public function create(): Arena;
}