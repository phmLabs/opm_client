<?php

use Whm\Opm\Client\Command\RunMessurement;

use Symfony\Component\Console\Application;

use Symfony\Component\Console\Tester\CommandTester;

use Whm\Opm\Client\Command\SetupConfig;

class RunMessurementTest extends PHPUnit_Framework_TestCase
{

    public function testRunMessurement ()
    {
        $application = new Application();
        $application->add(new RunMessurement());

        $command = $application->find('runMessurement');

        $commandTester = new CommandTester($command);

        $dialog = $command->getHelper('dialog');
        $dialog->setInputStream($this->getInputStream('Test\n'));

        $commandTester->execute(array('command' => $command->getName(), "config" => "config.yml"));

        $this->assertTrue(true);
    }

    private function getInputStream ($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }
}
