<?php

namespace App\Command;

use App\Command\Style\AppStyle;
use App\Command\Style\AppStyleInterface;
use App\Entity\Result;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'avato:test',
    description: 'Consults "hash" route',
)]
class AvatoTestCommand extends Command
{
    private AppStyleInterface $io;

    public function __construct(
        private HttpClientInterface $http_client,
        private RouterInterface $router,
        private ValidatorInterface $validator,
        private ManagerRegistry $doctrine,
        private SerializerInterface $serializer,
        string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('string', InputArgument::REQUIRED, 'string used in the first request')
            ->addOption('requests', null, InputOption::VALUE_REQUIRED, 'number of requests', 1)
            ->setHelp(
                "Consults \"hash\" route, by the number of times in the \"requests\" option, and store the results,\n"
                    . "    using \"string\" arg in the first request then in the next requests uses the generated hash in the previous one"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = $io = new AppStyle($input, $output, $this->serializer);

        $entity_manager = $this->doctrine->getManager();

        $requests = $input->getOption('requests');

        $string = $input->getArgument('string');

        $batch = new DateTime;

        for ($block = 1; $block <= $requests; $block++) {
            $result = new Result();
            $result->setString($string);

            if (!count($errors = $this->validator->validateProperty($result, 'string'))) {
                $response = $this->requestHash($result->getString());

                extract($response->toArray());

                $result
                    ->setBatch($batch)
                    ->setBlock($block)
                    ->setKey($key)
                    ->setHash($hash)
                    ->setAttempts($attempts);

                if (!count($errors = $this->validator->validate($result))) {
                    $entity_manager->persist($result);
                    $entity_manager->flush();

                    $io->object($result, ['groups' => 'show']);

                    $string = $result->getHash();

                    continue;
                }
            }

            $io->validationErrors($errors);

            return Command::FAILURE;
        }

        $io->success('Done');

        return Command::SUCCESS;
    }

    protected function requestHash(string $string)
    {
        $response = $this->http_client->request(
            Request::METHOD_GET,
            $this->router->generate(
                'hash',
                ['string' => $string],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );

        if ($response->getStatusCode() == 429) {
            $ratelimit_timestamp = $response->getHeaders(false)['x-ratelimit-retry-after'][0];
            $this->io->info('waiting, due rate limit, until ' . date('H:i:s', $ratelimit_timestamp));
            time_sleep_until($ratelimit_timestamp);

            return $this->requestHash($string);
        }

        return $response;
    }
}
