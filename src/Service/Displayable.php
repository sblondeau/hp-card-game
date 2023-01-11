<?php

namespace App\Service;

interface Displayable
{
    public function getName(): string;
    public function getDescription(): string;
    public function getImage(): string;
}