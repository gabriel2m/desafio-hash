<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AvatoTestCommandTest extends KernelTestCase
{
    public function testSuccess(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('avato:test');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'string' => 'test',
            '--requests' => '2'
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}
