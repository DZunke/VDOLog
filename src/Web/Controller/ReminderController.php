<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use VDOLog\Core\Domain\Game;

/**
 * @Route("/game")
 */
final class ReminderController extends AbstractController
{
    /**
     * @Route("/{game}/reminder/", name="reminder_index")
     * @ParamConverter("game", class=Game::class, options={"id" = "game"})
     */
    public function index(Game $game): Response
    {
        return $this->render('reminder/index.html.twig', ['game' => $game]);
    }

    /**
     * @Route("/reminder/remind", name="reminder_check")
     */
    public function check(): JsonResponse
    {
        return $this->json(['messages' => []]);
    }
}
