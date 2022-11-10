<?php

namespace App\Controller;

use App\Entity\Player;
use App\Service\ArenaFactory;
use App\Service\CardFactory;
use App\Service\PlayerFactory;
use App\Service\TurnSwitcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArenaController extends AbstractController
{
    #[Route('/', name: 'app_arena')]
    public function index(ArenaFactory $arenaFactory, TurnSwitcher $turnSwitcher): Response
    {
        $players = $arenaFactory->create();        
        $turnSwitcher->add($players);
        $turnSwitcher->next();
        return $this->render('fight/index.html.twig', [
            'decks' => $players,
        ]);
    }
}
