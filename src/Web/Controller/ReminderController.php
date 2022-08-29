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
use VDOLog\Core\Application\Game\DeleteReminder;
use VDOLog\Core\Domain\Game;
use VDOLog\Web\Form\Dto\Game\NewReminderDto;
use VDOLog\Web\Form\Game\NewReminderType;

use function assert;
use function is_string;

/** @Route("/game") */
final class ReminderController extends AbstractController
{
    public function __construct(private Game\ReminderRepository $reminderRepository)
    {
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
                'Eine neue Erinnerung mit dem Titel "' . $dto->title . '" wurde erstellt',
            );

            return $this->redirectToRoute('reminder_index', ['game' => $game->getId()]);
        }

        return $this->render('reminder/new.html.twig', ['game' => $game, 'form' => $form->createView()]);
    }

    /** @Route("/{game}/reminder/edit/{id}", name="reminder_edit") */
    public function edit(Request $request, MessageBusInterface $messageBus, Game\Reminder $reminder): Response
    {
        return new Response('NOT IMPLEMENTED', Response::HTTP_NOT_IMPLEMENTED);
    }

    /** @Route("/{game}/reminder/delete/{id}", name="reminder_delete") */
    public function delete(Request $request, MessageBusInterface $messageBus, Game\Reminder $reminder): Response
    {
        $token = $request->request->get('_token', '');
        assert(is_string($token));
        if ($this->isCsrfTokenValid('delete' . $reminder->getId(), $token)) {
            $messageBus->dispatch(new DeleteReminder($reminder));

            $this->addFlash(
                'success',
                'Die Erinnerung mit dem Titel "' . $reminder->getTitle() . '" wurde erfolgreich gelöscht.',
            );

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('reminder/delete.html.twig', ['reminder' => $reminder]);
    }

    /** @Route("/reminder/remind/{lastCheck}", name="reminder_check") */
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
