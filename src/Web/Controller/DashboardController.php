<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use VDOLog\Core\Domain\Game;

class DashboardController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route(path: '/', name: 'dashboard')]
    public function index(): Response
    {
        $games = $this->em->getRepository(Game::class)->findBy([], ['createdAt' => 'desc']);

        return $this->render('dashboard/index.html.twig', ['games' => $games]);
    }
}
