<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use VDOLog\Core\Domain\Location;
use VDOLog\Core\Domain\LocationRepository;
use VDOLog\Web\Form\Dto\LocationDto;
use VDOLog\Web\Form\LocationType;

/**
 * @Route("/location")
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class LocationController extends AbstractController
{
    public function __construct(
        private LocationRepository $locationRepository,
    ) {
    }

    /** @Route("/", name="location_list") */
    public function index(): Response
    {
        return $this->render(
            'location/index.html.twig',
            ['locations' => $this->locationRepository->findAll()],
        );
    }

    /** @Route("/create", name="location_create") */
    public function create(Request $request, MessageBusInterface $messageBus): Response
    {
        $dto  = new LocationDto();
        $form = $this->createForm(LocationType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toCreateCommand());

            $this->addFlash(
                'success',
                'Der Standort "' . $dto->name . '" wurde erfolgreich angelegt.',
            );

            return $this->redirectToRoute('location_list');
        }

        return $this->render(
            'location/create.html.twig',
            ['form' => $form->createView()],
        );
    }

    /** @Route("/{id}/edit", name="location_edit") */
    public function edit(Location $location, Request $request, MessageBusInterface $messageBus): Response
    {
        $dto  = LocationDto::fromLocation($location);
        $form = $this->createForm(LocationType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($dto->toUpdateCommand());

            $this->addFlash(
                'success',
                'Der Standort "' . $dto->name . '" wurde erfolgreich bearbeitet.',
            );

            return $this->redirectToRoute('location_list');
        }

        return $this->render(
            'location/edit.html.twig',
            ['location' => $location, 'form' => $form->createView()],
        );
    }
}
