<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocumentationController extends AbstractController
{
    #[Route('/', name: 'documentation')]
    public function index(): Response
    {
        return $this->render('documentation/index.html.twig', [
            'readme' => file_get_contents($this->getParameter('kernel.project_dir') . '/README.md'),
        ]);
    }
}
