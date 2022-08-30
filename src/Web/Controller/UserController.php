<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use VDOLog\Core\Application\User\ChangePassword;
use VDOLog\Core\Application\User\DeleteUser;
use VDOLog\Core\Domain\User;
use VDOLog\Core\Domain\UserRepository;
use VDOLog\Web\Form\ChangePasswordType;
use VDOLog\Web\Form\CreateUserType;
use VDOLog\Web\Form\Dto\ChangePasswordDto;
use VDOLog\Web\Form\Dto\CreateUserDto;
use VDOLog\Web\Form\Dto\EditUserDto;
use VDOLog\Web\Form\Dto\UserProfileDto;
use VDOLog\Web\Form\EditUserType;
use VDOLog\Web\Form\UserProfileType;

use function assert;
use function is_string;

#[Route(path: '/security/user')]
#[Security("is_granted('ROLE_ADMIN')")]
final class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private User\CurrentUserProvider $currentUserProvider,
    ) {
    }

    #[Route(path: '/', name: 'user_list')]
    public function index(): Response
    {
        return $this->render(
            'user/index.html.twig',
            ['users' => $this->userRepository->findAll()],
        );
    }

    #[Route(path: '/create', name: 'user_create')]
    public function create(Request $request, MessageBusInterface $messageBus): Response
    {
        $dto  = new CreateUserDto();
        $form = $this->createForm(CreateUserType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toCommand());

            $this->addFlash(
                'success',
                'Der/Die NutzerIn mit der E-Mail-Adresse "' . $dto->email . '" wurde erfolgreich angelegt.',
            );

            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'user/create.html.twig',
            ['form' => $form->createView()],
        );
    }

    #[Route(path: '/{id}/edit/', name: 'user_edit')]
    public function edit(User $user, Request $request, MessageBusInterface $messageBus): Response
    {
        $dto  = EditUserDto::fromObject($user);
        $form = $this->createForm(EditUserType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toCommand());

            $this->addFlash(
                'success',
                'Der/Die NutzerIn mit der E-Mail-Adresse "' . $dto->email . '" wurde erfolgreich bearbeitet.',
            );

            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'user/edit.html.twig',
            ['user' => $user, 'form' => $form->createView()],
        );
    }

    #[Route(path: '/{id}/delete', name: 'user_delete', methods: ['GET', 'DELETE'])]
    public function delete(User $user, Request $request, MessageBusInterface $messageBus): Response
    {
        if ($this->currentUserProvider->getCurrentUser()->getId() === $user->getId()) {
            $this->addFlash(
                'warning',
                'Der/Die NutzerIn entspricht dem aktuell verwendeten Nutzer und kann nicht gelöscht werden',
            );

            return $this->redirectToRoute('user_list');
        }

        $token = $request->request->get('_token', '');
        assert(is_string($token));

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $token)) {
            $messageBus->dispatch(new DeleteUser($user->getId()));

            $this->addFlash(
                'success',
                'Der/Die NutzerIn mit der E-Mail-Adresse "'
                . $user->getEmail() . '" wurde erfolgreich gelöscht.',
            );

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/delete.html.twig', ['user' => $user]);
    }

    #[Route(path: '/{id}/change_password', name: 'user_change_password')]
    public function password(User $user, Request $request, MessageBusInterface $messageBus): Response
    {
        $dto  = ChangePasswordDto::fromObject($user);
        $form = $this->createForm(ChangePasswordType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toCommand());

            $this->addFlash(
                'success',
                'Der/Die NutzerIn mit der E-Mail-Adresse "' . $user->getEmail() . '" wurde erfolgreich bearbeitet.',
            );

            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'user/change_password.html.twig',
            ['user' => $user, 'form' => $form->createView()],
        );
    }

    #[Route(path: '/profile', name: 'user_profile')]
    public function profile(User\CurrentUserProvider $currentUserProvider, Request $request, MessageBusInterface $messageBus): Response
    {
        $dto  = UserProfileDto::fromObject($currentUserProvider->getCurrentUser());
        $form = $this->createForm(UserProfileType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toUpdateProfileCommand());
            $passwordChangeCommand = $dto->toChangePasswordCommand();
            if ($passwordChangeCommand instanceof ChangePassword) {
                $messageBus->dispatch($passwordChangeCommand);
            }

            $this->addFlash(
                'success',
                'Ihr Profil wurde erfolgreich aktualisiert',
            );

            return $this->redirectToRoute('user_profile');
        }

        return $this->render(
            'user/profile.html.twig',
            ['form' => $form->createView()],
        );
    }
}
