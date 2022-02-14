<?php

namespace App\Repository;

use App\Entity\Result;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * @method Result|null find($id, $lockMode = null, $lockVersion = null)
 * @method Result|null findOneBy(array $criteria, array $orderBy = null)
 * @method Result[]    findAll()
 * @method Result[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultRepository extends ServiceEntityRepository
{
    public function __construct(
        private ContainerBagInterface $params,
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Result::class);
    }

    public function getResultsPaginator(int $page, int $attempts = null): Paginator
    {
        if ($page < 1) {
            throw new InvalidArgumentException('"page" can\'t be less then 1');
        }

        $per_page = $this->params->get('results_paginator_per_page');
        $query_builder = $this->createQueryBuilder('r');

        if (isset($attempts)) {
            $query_builder
                ->andWhere('r.attempts < :attempts')
                ->setParameter('attempts', $attempts);
        }

        return new Paginator(
            $query_builder
                ->orderBy('r.batch', 'DESC')
                ->addOrderBy('r.block', 'DESC')
                ->setMaxResults($per_page)
                ->setFirstResult($per_page * ($page - 1))
                ->getQuery()
        );
    }
}
