<?php

namespace App\Controller;

use App\Entity\Player;
use App\Service\Actioner;
use App\Service\Actions\Fight;
use App\Service\Actions\Selectionnable;
use App\Service\ArenaFactory;
use App\Service\CardFactory;
use App\Service\PlayerFactory;
use App\Service\PlayerSwitcher;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArenaController extends AbstractController
{
    #[Route('/', name: 'app_arena')]
    public function index(ArenaFactory $arenaFactory, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();

        if (!$session->has('actioner')) {
            $playerSwitcher = new PlayerSwitcher();
            $playerSwitcher->setPlayers($arenaFactory->create());

            $session->set('actioner', new Actioner());
            $session->get('actioner')->setPlayerSwitcher($playerSwitcher);
        } elseif ($session->get('actioner')->getActionnable()) {
            $possibleTargets = $session->get('actioner')->getActionnable()->getPossibleTargets();
        }

        return $this->render('fight/index.html.twig', [
            'playerSwitcher' => $session->get('actioner')->getPlayerSwitcher(),
            'possibleTargets' => $possibleTargets ?? null,
        ]);
    }

    #[Route('/play/{id}', name: 'app_play')]
    public function play(mixed $id = null, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();

        $actioner = $session->get('actioner');
        $selectionnable = $actioner->getPlayerSwitcher()->findSelectionnable($id);

        try {
            if (!$actioner->getAttacker()) {
                $actioner->setAttacker($selectionnable);
                $this->addFlash('success', 'attacker selected');
            } elseif (!$actioner->getActionnable()) {
                // todo gestion des autres cards actions
                $selectionnable ??= new Fight();
                $actioner->setActionnable($selectionnable);
                $this->addFlash('success', 'action selected');
            } elseif (!$actioner->getTarget()) {
                // todo get targetables et affichage
                // TODO exception gestion si mauvais cliquage (sur allier par ex)
                // TODO mauvais cliquage de tour
                $actioner->setTarget($selectionnable);

                $this->addFlash('success', 'turn terminated');
            }
        } catch (Exception $exception) {
            $error = $exception->getMessage();
            $this->addFlash('error', $error);
        }

        $session->set('actioner', $actioner);

        return $this->redirectToRoute('app_arena');
    }

    #[Route('/reset', name: 'reset')]
    public function reset(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $session->clear();

        return $this->redirectToRoute('app_arena');
    }
}
