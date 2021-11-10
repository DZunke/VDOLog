<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use VDOLog\Core\Domain\UserRepository;
use VDOLog\Web\Form\CreateUserType;
use VDOLog\Web\Form\Dto\CreateUserDto;

/**
 * @Route("/security/user")
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class UserController extends AbstractController
{
    public function __construct(private UserRepository $userRepository)
    {
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
}
