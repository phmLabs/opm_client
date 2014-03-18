<?php

/**
 * This file is part of the Open Performance Monitor Client package
 *
 * The Open Performance Monitor collects data to measure the performance of websites
 *
 * @package OPMCLient
 */

namespace Whm\Opm\Client\Modules\Messure\HttpArchive\Command;

use Whm\Opm\Client\Server\Server;
use Whm\Opm\Client\Config\Config;
use Whm\Opm\Client\Browser\PhantomJS;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

/**
 * ProcessUrl
 *
 * Process an url and send the result (har file) to an opm server
 *
 * @category Command
 * @package  OPMClient
 * @license    https://raw.github.com/thewebhatesme/opm_server/master/LICENSE
 * @example  $./bin/client processUrl
 * @version   GIT: $Id$
 * @since       Date: 2014-03-12
 * @author    Nils Langner <nils.langner@phmlabs.com>
 */
class ProcessUrl extends Command
{

    /**
     * {@inheritDoc}
     */
    protected function configure ()
    {
        $this->setName('messure:httpArchive:processUrl')
            ->setDescription('Process an url and send the result (har file) to an opm server.')
            ->addArgument('clientId', InputArgument::REQUIRED, 'The client id')
            ->addArgument('host', InputArgument::REQUIRED, 'The server adress')
            ->addArgument('phantomJS', InputArgument::REQUIRED, 'The path to your PhantomJS executable')
            ->addArgument('url', InputArgument::REQUIRED, 'The url that has to be fetched');
    }

    /**
     * Execute task
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @throws DomainException
     * 
     * @return void 
     */
    protected function execute (InputInterface $input, OutputInterface $output)
    {
//         $config = Config::createFromFile($input->getArgument('config'));

        $phantom = new PhantomJS($input->getArgument('phantomJS'));
        $httpArchive = $phantom->createHttpArchive($input->getArgument('url'));

        $server = new Server($input->getArgument('host'), $input->getArgument('clientId'));

        try {
            $server->addMessurement($input->getArgument('url'), $httpArchive);
        } catch (\DomainException $e) {
            throw $e;
        }
    }
}
