<?php

namespace App\Service;

use Symfony\Component\String\ByteString;

class HashGenerator implements HashGeneratorInterface
{
    /**
     * Returns a array with "hash", "key" and "attempts"
     * 
     * Concats the input string and a random 8 characters string (called "key") and calculates its md5 hash,
     * if this hash starts with "0000" returns ["hash", "key" and "attempts"]
     * otherwise try again with a new "key"
     */
    public function brTecParHash(string $string): array
    {
        $attempts = 0;
        do {
            $key = ByteString::fromRandom(8)->toString();
            $hash = md5($string . $key);
            $attempts++;
        } while (!str_starts_with($hash, "0000"));

        return compact('hash', 'key', 'attempts');
    }
}
