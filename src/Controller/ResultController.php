<?php

namespace App\Controller;

use App\Repository\ResultRepository;
use App\Service\ApiPaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResultController extends AbstractController
{
    #[Route('/results', name: 'results')]
    public function index(
        Request $request,
        ResultRepository $resultRepository,
        ApiPaginatorInterface $apiPaginator
    ): JsonResponse {
        $page_param = 'page';
        $page = max(1, $request->query->getInt($page_param, 1));
        $attempts = $request->query->getInt('attempts') ?: null;

        return $this->json(
            data: $apiPaginator->paginate(
                $resultRepository->getResultsPaginator($page, $attempts),
                $request,
                $page,
                $page_param
            ),
            context: ['groups' => 'show']
        );
    }
}
