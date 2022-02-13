<?php

namespace App\Command\Style;

use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface AppStyleInterface extends StyleInterface
{
    public function validationErrors(ConstraintViolationListInterface $errors);

    public function object(mixed $object, array $context = []);

    public function info(string|array $message);
}
