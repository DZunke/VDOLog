<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use VDOLog\Core\Application\User\DeleteUser;
use VDOLog\Core\Domain\User;
use VDOLog\Core\Domain\UserRepository;
use VDOLog\Web\Form\CreateUserType;
use VDOLog\Web\Form\Dto\CreateUserDto;
use VDOLog\Web\Form\Dto\EditUserDto;
use VDOLog\Web\Form\EditUserType;

use function assert;
use function is_string;

/**
 * @Route("/security/user")
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private User\CurrentUserProvider $currentUserProvider
    ) {
    }

    /**
     * @Route("/", name="user_list")
     */
    public function index(): Response
    {
        return $this->render(
            'user/index.html.twig',
            ['users' => $this->userRepository->findAll()]
        );
    }

    /**
     * @Route("/create", name="user_create")
     */
    public function create(Request $request, MessageBusInterface $messageBus): Response
    {
        $dto  = new CreateUserDto();
        $form = $this->createForm(CreateUserType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toCommand());

            $this->addFlash(
                'success',
                'Der/Die NutzerIn mit der E-Mail-Adresse "' . $dto->email . '" wurde erfolgreich angelegt.'
            );

            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'user/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/{id}/edit/", name="user_edit")
     */
    public function edit(User $user, Request $request, MessageBusInterface $messageBus): Response
    {
        $dto  = EditUserDto::fromObject($user);
        $form = $this->createForm(EditUserType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toCommand());

            $this->addFlash(
                'success',
                'Der/Die NutzerIn mit der E-Mail-Adresse "' . $dto->email . '" wurde erfolgreich bearbeitet.'
            );

            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'user/edit.html.twig',
            ['user' => $user, 'form' => $form->createView()]
        );
    }

    /**
     * @Route("/{id}/delete", name="user_delete", methods={"GET", "DELETE"})
     */
    public function delete(User $user, Request $request, MessageBusInterface $messageBus): Response
    {
        if ($this->currentUserProvider->getCurrentUser()->getId() === $user->getId()) {
            $this->addFlash(
                'warning',
                'Der/Die NutzerIn entspricht dem aktuell verwendeten Nutzer und kann nicht gelöscht werden'
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
                . $user->getEmail() . '" wurde erfolgreich gelöscht.'
            );

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/delete.html.twig', ['user' => $user]);
    }
}
