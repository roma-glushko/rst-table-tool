<?php

namespace RstTableToolBundle\Command;

use RstTableToolBundle\Service\GenerateRstTableByPathService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateRstTableCommand
 *
 * Provides command for creating docker container from an image
 */
class GenerateRstTableCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('rst:table:generate');
        $this->setDescription('Generate RST Table of files by path to their root directory');
        $this->addArgument('path', InputArgument::REQUIRED, 'path to root directory');
        $this->addArgument('output-file', InputArgument::REQUIRED, 'filename to save RST table');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');
        $outputFile = $input->getArgument('output-file');

        $this->getGenerateRstTablebyPathService()->execute($path, $outputFile);

        return 0;
    }

    /**
     * @return GenerateRstTableByPathService
     */
    private function getGenerateRstTableByPathService()
    {
        return new GenerateRstTableByPathService();
    }
}