<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/checklist')]
class ChecklistController extends AbstractController
{
    #[Route(path: '/notify', name: 'check_notifications')]
    public function check(): JsonResponse
    {
        return $this->json(['messages' => []]);
    }
}
