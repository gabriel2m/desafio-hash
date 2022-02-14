<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ApiPaginator implements ApiPaginatorInterface
{
    public function __construct(
        private RouterInterface $router,
        private ContainerBagInterface $params
    ) {
    }

    public function paginate(
        DoctrinePaginator $doctrine_paginator,
        Request $request,
        int $current_page,
        string $page_param = 'page'
    ): array {
        $params = $request->query->all();
        $per_page = $this->params->get('results_paginator_per_page');
        $route = $request->get('_route');
        $count = $doctrine_paginator->count();
        $have_next = ($per_page * $current_page) < $count;
        $have_prev = $count > $per_page && $current_page > 1;

        return [
            'data' => $doctrine_paginator->getQuery()->getResult(),
            'meta' => [
                'count' => $count,
                'next' => $have_next
                    ? $this->router->generate(
                        $route,
                        [$page_param => $current_page + 1] + $params
                    )
                    : null,
                'prev' => $have_prev
                    ? $this->router->generate(
                        $route,
                        [$page_param => min($current_page - 1, (int)(($count - 1) / $per_page) + 1)] + $params
                    )
                    : null
            ]
        ];
    }
}
