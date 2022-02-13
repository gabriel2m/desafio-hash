<?php

namespace App\Controller;

use App\Service\HashGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;

/**
 * Manage requests to the "hash" resource
 */
class HashController extends AbstractController
{
    /**
     * Returns a array with "hash", "key" and "attempts"
     * 
     * Concats the input string and a random 8 characters string (called "key") and calculates its hash,  
     * until find a hash that starts with "0000",  
     * then returns:
     *  ```json
     *      {
     *          "hash": string, 
     *          "key": string,
     *          "attempts": int
     *      }
     * ```
     *
     **/
    #[Route('/hash/{string}', methods: Request::METHOD_GET, name: 'hash')]
    public function hash(
        Request $request,
        RateLimiterFactory $hashLimiter,
        HashGeneratorInterface $hashGenerator,
        string $string
    ): JsonResponse {
        $limiter = $hashLimiter->create($request->getClientIp());

        $limit = $limiter->consume();

        $headers = [
            'X-RateLimit-Remaining' => $limit->getRemainingTokens(),
            'X-RateLimit-Retry-After' => $limit->getRetryAfter()->getTimestamp(),
            'X-RateLimit-Limit' => $limit->getLimit(),
        ];

        if (false === $limit->isAccepted())
            return $this->json(
                ['message' => "Too many Attempts"],
                Response::HTTP_TOO_MANY_REQUESTS,
                $headers
            );

        return $this->json(
            $hashGenerator->brTecParHash($string),
            Response::HTTP_OK,
            $headers
        );
    }
}
