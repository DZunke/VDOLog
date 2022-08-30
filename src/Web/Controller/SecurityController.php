<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'vdo_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('dashboard');
        }

        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error_message' => $authenticationUtils->getLastAuthenticationError(),
            ],
        );
    }

    #[Route(path: '/logout', name: 'vdo_logout')]
    public function logout(): Response
    {
        throw new LogicException('empty by intention, firewall will handle logout');
    }
}
