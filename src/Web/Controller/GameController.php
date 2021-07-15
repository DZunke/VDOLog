<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use VDOLog\Core\Application\Game\DeleteGame;
use VDOLog\Core\Application\Game\LockGame;
use VDOLog\Core\Application\Game\UnlockGame;
use VDOLog\Core\Domain\Game;
use VDOLog\Web\Form\CreateGameType;
use VDOLog\Web\Form\Dto\CreateGameDto;
use VDOLog\Web\Form\Dto\EditGameDto;
use VDOLog\Web\Form\EditGameType;
use VDOLog\Web\Model\GameExporter;

use function assert;
use function is_string;

/**
 * @Route("/game")
 */
class GameController extends AbstractController
{
    /**
     * @Route("/new", name="game_new", methods={"GET","POST"})
     */
    public function new(Request $request, MessageBusInterface $messageBus): Response
    {
        $dto  = new CreateGameDto();
        $form = $this->createForm(CreateGameType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toCommand());

            $this->addFlash(
                'success',
                'Das Spiel mit dem Namen "' . $dto->name . '" wurde erfolgreich gespeichert.'
            );

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('game/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="game_edit", methods={"GET","POST"})
     */
    public function edit(MessageBusInterface $messageBus, Request $request, Game $game): Response
    {
        $dto  = new EditGameDto($game);
        $form = $this->createForm(EditGameType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toCommand());

            $this->addFlash(
                'success',
                'Das Spiel mit dem Namen "' . $dto->name . '" wurde erfolgreich gespeichert.'
            );

            return $this->redirectToRoute('dashboard', [
                'id' => $game->getId(),
            ]);
        }

        return $this->render('game/edit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="game_delete", methods={"GET", "DELETE"})
     */
    public function delete(MessageBusInterface $messageBus, Request $request, Game $game): Response
    {
        $token = $request->request->get('_token', '');
        assert(is_string($token));
        if ($this->isCsrfTokenValid('delete' . $game->getId(), $token)) {
            $messageBus->dispatch(new DeleteGame($game->getId()));

            $this->addFlash(
                'success',
                'Das Spiel mit dem Namen "' . $game->getName() . '" wurde erfolgreich gelöscht.'
            );

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('game/delete.html.twig', ['game' => $game]);
    }

    /**
     * @Route("/{id}/lock", name="game_lock", methods={"GET"})
     */
    public function lock(MessageBusInterface $messageBus, Game $game): Response
    {
        $messageBus->dispatch(new LockGame($game->getId()));

        $this->addFlash(
            'success',
            'Das Spiel mit dem Namen "' . $game->getName() . '" wurde erfolgreich gesperrt.'
        );

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/{id}/unlock", name="game_unlock", methods={"GET"})
     */
    public function unlock(MessageBusInterface $messageBus, Game $game): Response
    {
        $messageBus->dispatch(new UnlockGame($game->getId()));

        $this->addFlash(
            'success',
            'Das Spiel mit dem Namen "' . $game->getName() . '" wurde erfolgreich entsperrt.'
        );

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/{id}/export", name="game_export", methods={"GET"})
     */
    public function export(Game $game): StreamedResponse
    {
        $spreadsheet = (new GameExporter())->export($game);
        $writer      = new Xlsx($spreadsheet);

        $response = new StreamedResponse(static function () use ($writer): void {
            $writer->save('php://output');
        });
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $game->getId() . '.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
