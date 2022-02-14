<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Component\HttpFoundation\Request;

interface ApiPaginatorInterface
{
    public function paginate(
        DoctrinePaginator $doctrine_paginator,
        Request $request,
        int $current_page,
        string $page_param
    ): array;
}
