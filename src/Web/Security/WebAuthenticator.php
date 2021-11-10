<?php

declare(strict_types=1);

namespace VDOLog\Web\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use VDOLog\Core\Domain\UserRepository;

class WebAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private RouterInterface $router,
        private UserRepository $userRepository
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/login' && $request->isMethod(Request::METHOD_POST);
    }

    public function authenticate(Request $request): PassportInterface
    {
        $email    = (string) $request->request->get('email');
        $password = (string) $request->request->get('password');

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $email = (string) $request->request->get('email');
        $this->userRepository->updateLastLogin($email);

        return new RedirectResponse($this->router->generate('dashboard'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        $request->getSession()->set(Security::LAST_USERNAME, (string) $request->request->get('email'));

        return new RedirectResponse($this->router->generate('vdo_login'));
    }
}
