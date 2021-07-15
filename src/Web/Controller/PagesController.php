<?php

declare(strict_types=1);

namespace VDOLog\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function file_exists;
use function file_get_contents;

use const DIRECTORY_SEPARATOR;

/**
 * @Route("/pages")
 */
final class PagesController extends AbstractController
{
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @Route("/changelog", name="changelog")
     */
    public function changelog(Request $request): Response
    {
        $changelog = file_get_contents(
            $this->projectDir . DIRECTORY_SEPARATOR . 'CHANGELOG.' . $request->getDefaultLocale() . '.md'
        );

        $currentChangelogPath = $this->projectDir . DIRECTORY_SEPARATOR . 'CHANGELOG.' . $request->getLocale() . '.md';
        if (file_exists($currentChangelogPath)) {
            $changelog = file_get_contents($currentChangelogPath);
        }

        return $this->render(
            'pages/changelog.html.twig',
            ['changelog' => $changelog]
        );
    }
}
