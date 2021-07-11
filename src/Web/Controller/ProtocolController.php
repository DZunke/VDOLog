<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\ProtocolRepository;
use VDOLog\Web\Form\Dto\AddProtocolDto;
use VDOLog\Web\Form\ProtocolType;

/**
 * @Route("/game/{game}/protocol")
 */
class ProtocolController extends AbstractController
{
    private ProtocolRepository $protocolRepository;

    public function __construct(ProtocolRepository $protocolRepository)
    {
        $this->protocolRepository = $protocolRepository;
    }

    /**
     * @Route("/", name="protocol_index")
     * @ParamConverter("game", class=Game::class, options={"id" = "game"})
     */
    public function index(MessageBusInterface $messageBus, Request $request, Game $game): Response
    {
        $dto  = new AddProtocolDto($game);
        $form = $this->createForm(ProtocolType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toCommand());

            return $this->redirectToRoute('protocol_index', ['game' => $game->getId()]);
        }

        return $this->render('protocol/index.html.twig', [
            'game' => $game,
            'protocol_list' => $this->protocolRepository->findForListing($game),
            'form' => $form->createView(),
        ]);
    }
}
