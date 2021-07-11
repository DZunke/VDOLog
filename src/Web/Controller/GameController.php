<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use DateTimeImmutable;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use VDOLog\Web\Entity\Game;
use VDOLog\Web\Form\GameType;
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
    public function new(Request $request): Response
    {
        $form = $this->createForm(GameType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($form->getData());
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Das Spiel mit dem Namen "' . $form->getData()->getName() . '" wurde erfolgreich gespeichert.'
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
    public function edit(Request $request, Game $game): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Das Spiel mit dem Namen "' . $game->getName() . '" wurde erfolgreich gespeichert.'
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
    public function delete(Request $request, Game $game): Response
    {
        $token = $request->request->get('_token', '');
        assert(is_string($token));
        if ($this->isCsrfTokenValid('delete' . $game->getId(), $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($game);
            $entityManager->flush();

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
    public function lock(Game $game): Response
    {
        $game->setClosedAt(new DateTimeImmutable());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'success',
            'Das Spiel mit dem Namen "' . $game->getName() . '" wurde erfolgreich gesperrt.'
        );

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/{id}/unlock", name="game_unlock", methods={"GET"})
     */
    public function unlock(Game $game): Response
    {
        $game->setClosedAt(null);
        $this->getDoctrine()->getManager()->flush();

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
