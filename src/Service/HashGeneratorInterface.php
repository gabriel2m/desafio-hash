<?php

namespace App\Service;

interface HashGeneratorInterface
{
    /**
     * Returns a array with "hash", "key" and "attempts"
     */
    public function brTecParHash(string $string): array;
}
