<?php

namespace App\Command\Style;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Output decorator helpers for the Symfony Style Guide
 */
class AppStyle extends SymfonyStyle implements AppStyleInterface
{
    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        private SerializerInterface $serializer
    ) {
        parent::__construct($input, $output);
    }

    public function validationErrors(ConstraintViolationListInterface $errors): void
    {
        foreach ($errors as $error)
            $this->error($error);
    }

    public function object(mixed $object, array $context = []): void
    {
        $data = json_decode(
            $this->serializer->serialize($object, 'json', $context),
            true
        );
        $this->table(
            array_keys($data),
            [$data]
        );
    }
}
