<?php

namespace App\Controller;

use App\Entity\Player;
use App\Service\Actioner;
use App\Service\Actions\Fight;
use App\Service\Actions\Selectionnable;
use App\Service\Factory\ArenaFactory;
use App\Service\Factory\ArenaOneFactory;
use App\Service\Factory\ActionerFactory;
use App\Service\PlayerSwitcher;
use Exception;
use SplObjectStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TypeError;

class ArenaController extends AbstractController
{
    #[Route('/', name: 'app_arena')]
    public function index(ArenaOneFactory $arenaOneFactory, RequestStack $requestStack, ActionerFactory $actionerFactory): Response
    {
        $session = $requestStack->getSession();

        if (!$session->has('actioner')) {
            $session->set('actioner', $actionerFactory->create($arenaOneFactory));
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
            } elseif($actioner->getAttacker() === $selectionnable) {
                $actioner->setAttacker($selectionnable);
                $this->addFlash('success', 'attacker unselected');
            } elseif (!$actioner->getActionnable()) {
                // todo gestion des autres cards actions
                $selectionnable ??= new Fight();
                $actioner->setActionnable($selectionnable);
                $this->addFlash('success', 'action selected');
            } elseif (!$actioner->getTarget()) {
                $actioner->setTarget($selectionnable);
                $this->addFlash('success', 'turn terminated');
            }
        } catch (TypeError $typeError) {
            $error = 'Mauvaise carte séléctionnée';
            $this->addFlash('danger', $error);
        } catch (Exception $exception) {
            $error = $exception->getMessage();
            $this->addFlash('danger', $error);
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
