<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use VDOLog\Core\Application\Game\AccessScanPointCreate;
use VDOLog\Core\Application\Game\DeleteGame;
use VDOLog\Core\Application\Game\LockGame;
use VDOLog\Core\Application\Game\Model\AccessScanPointCsvParser;
use VDOLog\Core\Application\Game\UnlockGame;
use VDOLog\Core\Domain\Game;
use VDOLog\Web\Form\CreateGameType;
use VDOLog\Web\Form\Dto\CreateGameDto;
use VDOLog\Web\Form\Dto\EditGameDto;
use VDOLog\Web\Form\EditGameType;
use VDOLog\Web\Form\Game\ImportAccessScanPointsType;
use VDOLog\Web\Model\Chart\GameEntrance;
use VDOLog\Web\Model\Chart\GameExits;
use VDOLog\Web\Model\GameExporter;

use function assert;
use function file_get_contents;
use function is_string;
use function ob_get_clean;
use function ob_start;

#[Route(path: '/game')]
class GameController extends AbstractController
{
    #[Route(path: '/new', name: 'game_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MessageBusInterface $messageBus): Response
    {
        $dto  = new CreateGameDto();
        $form = $this->createForm(CreateGameType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toCommand());

            $this->addFlash(
                'success',
                'Das Spiel mit dem Namen "' . $dto->name . '" wurde erfolgreich gespeichert.',
            );

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('game/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'game_edit', methods: ['GET', 'POST'])]
    public function edit(MessageBusInterface $messageBus, Request $request, Game $game): Response
    {
        $dto  = new EditGameDto($game);
        $form = $this->createForm(EditGameType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toCommand());

            $this->addFlash(
                'success',
                'Das Spiel mit dem Namen "' . $dto->name . '" wurde erfolgreich gespeichert.',
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

    #[Route(path: '/{id}/delete', name: 'game_delete', methods: ['GET', 'DELETE'])]
    public function delete(MessageBusInterface $messageBus, Request $request, Game $game): Response
    {
        $token = $request->request->get('_token', '');
        assert(is_string($token));
        if ($this->isCsrfTokenValid('delete' . $game->getId(), $token)) {
            $messageBus->dispatch(new DeleteGame($game->getId()));

            $this->addFlash(
                'success',
                'Das Spiel mit dem Namen "' . $game->getName() . '" wurde erfolgreich gelöscht.',
            );

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('game/delete.html.twig', ['game' => $game]);
    }

    #[Route(path: '/{id}/lock', name: 'game_lock', methods: ['GET'])]
    public function lock(MessageBusInterface $messageBus, Game $game): Response
    {
        $messageBus->dispatch(new LockGame($game->getId()));

        $this->addFlash(
            'success',
            'Das Spiel mit dem Namen "' . $game->getName() . '" wurde erfolgreich gesperrt.',
        );

        return $this->redirectToRoute('dashboard');
    }

    #[Route(path: '/{id}/unlock', name: 'game_unlock', methods: ['GET'])]
    public function unlock(MessageBusInterface $messageBus, Game $game): Response
    {
        $messageBus->dispatch(new UnlockGame($game->getId()));

        $this->addFlash(
            'success',
            'Das Spiel mit dem Namen "' . $game->getName() . '" wurde erfolgreich entsperrt.',
        );

        return $this->redirectToRoute('dashboard');
    }

    #[Route(path: '/{id}/export', name: 'game_export', methods: ['GET'])]
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

    #[Route(path: '/{id}/statistics', name: 'game_statistics', methods: ['GET'])]
    public function statistics(Game $game, ChartBuilderInterface $chartBuilder): Response
    {
        return $this->render(
            'game/statistics.html.twig',
            [
                'game' => $game,
                'entrance_chart' => GameEntrance::fromGame($chartBuilder, $game),
                'exists_chart' => GameExits::fromGame($chartBuilder, $game),
            ],
        );
    }

    #[Route(path: '/{id}/import-access-scan-points', name: 'game_import_access_scan_points')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function importAccessScanPoints(Request $request, Game $game, MessageBusInterface $messageBus): Response
    {
        $form = $this->createForm(ImportAccessScanPointsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->files->has('import_access_scan_points')) {
                $uploadedFile = $request->files->get('import_access_scan_points')['csv'];
                if ($uploadedFile->getMimeType() === ImportAccessScanPointsType::EXCEL_MIME_TYPE) {
                    $reader      = IOFactory::createReader('Xlsx');
                    $spreadsheet = $reader->load($uploadedFile->getPathname());

                    $writer = IOFactory::createWriter($spreadsheet, 'Csv');
                    assert($writer instanceof Csv);
                    $writer->setSheetIndex(0);
                    $writer->setDelimiter(';');
                    $writer->setEnclosure('');

                    ob_start();
                    $writer->save('php://output');
                    $csvContent = ob_get_clean();
                } else {
                    $csvContent = file_get_contents($uploadedFile->getPathname());
                }

                assert(is_string($csvContent));

                $csvParser              = new AccessScanPointCsvParser();
                $parsedAccessPointScans = $csvParser->parse($game->getTimeFrame()->getEventStartsAt(), $csvContent);

                foreach ($parsedAccessPointScans as $accessScanPoint) {
                    $messageBus->dispatch(new AccessScanPointCreate(
                        $game->getId(),
                        $accessScanPoint->getTime(),
                        $accessScanPoint->getEntrances(),
                        $accessScanPoint->getExits(),
                    ));
                }

                $this->addFlash(
                    'success',
                    'Die Einlasstatistiken wurden erfolgreich importiert.',
                );

                return $this->redirectToRoute('game_statistics', ['id' => $game->getId()]);
            }
        }

        return $this->render(
            'game/import_access_scan_points.html.twig',
            ['game' => $game, 'form' => $form->createView()],
        );
    }
}
