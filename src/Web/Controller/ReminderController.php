<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use DateTimeImmutable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use VDOLog\Core\Domain\Game;
use VDOLog\Web\Form\Dto\Game\NewReminderDto;
use VDOLog\Web\Form\Game\NewReminderType;

/**
 * @Route("/game")
 */
final class ReminderController extends AbstractController
{
    private Game\ReminderRepository $reminderRepository;

    public function __construct(Game\ReminderRepository $reminderRepository)
    {
        $this->reminderRepository = $reminderRepository;
    }

    /**
     * @Route("/{game}/reminder/", name="reminder_index")
     * @ParamConverter("game", class=Game::class, options={"id" = "game"})
     */
    public function index(Game $game): Response
    {
        return $this->render('reminder/index.html.twig', ['game' => $game]);
    }

    /**
     * @Route("/{game}/reminder/new", name="reminder_new")
     * @ParamConverter("game", class=Game::class, options={"id" = "game"})
     */
    public function new(Request $request, MessageBusInterface $messageBus, Game $game): Response
    {
        $dto  = new NewReminderDto($game);
        $form = $this->createForm(NewReminderType::class, $dto, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toCommand());
            $this->addFlash(
                'success',
                'Eine neue Erinnerung mit dem Titel "' . $dto->title . '" wurde erstellt'
            );

            return $this->redirectToRoute('reminder_index', ['game' => $game->getId()]);
        }

        return $this->render('reminder/new.html.twig', ['game' => $game, 'form' => $form->createView()]);
    }

    /**
     * @Route("/reminder/remind/{lastCheck}", name="reminder_check")
     */
    public function check(DateTimeImmutable $lastCheck): JsonResponse
    {
        $reminderArr = $this->reminderRepository->findUnsentRemindersSince($lastCheck);
        $messages    = [];

        foreach ($reminderArr as $reminder) {
            $messages[] = [
                'id' => $reminder->getId(),
                'title' => $reminder->getTitle(),
                'message' => $reminder->getMessage(),
            ];
        }

        return $this->json(['messages' => $messages]);
    }
}
