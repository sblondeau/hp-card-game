<?php

namespace App\Service\Actions;

interface Selectionnable
{
    public function getIdentifier(): string;
    public function getName(): string;
}