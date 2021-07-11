<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use VDOLog\Web\Entity\Game;
use VDOLog\Web\Entity\Protocol;
use VDOLog\Web\Form\ProtocolType;

/**
 * @Route("/game/{game}/protocol")
 */
class ProtocolController extends AbstractController
{
    /**
     * @Route("/", name="protocol_index")
     * @ParamConverter("game", class="VDOLog\Web\Entity\Game", options={"id" = "game"})
     */
    public function index(Request $request, Game $game): Response
    {
        $form = $this->createForm(ProtocolType::class, Protocol::create($game, ''));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($form->getData());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('protocol_index', ['game' => $game->getId()]);
        }

        return $this->render('protocol/index.html.twig', [
            'game' => $game,
            'protocol_list' => $this->getDoctrine()->getRepository(Protocol::class)->findForListing($game),
            'form' => $form->createView(),
        ]);
    }
}
